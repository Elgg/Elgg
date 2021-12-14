<?php
/**
 *
 * Interface for writing files, retrieving files and checking caches
 *
 */
namespace CssCrush;

class IO
{
    protected $process;

    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    public function init()
    {
        $this->process->cacheFile = "{$this->process->output->dir}/.csscrush";
    }

    public function getOutputDir()
    {
        $outputDir = $this->process->options->output_dir;

        return $outputDir ? $outputDir : $this->process->input->dir;
    }

    public function getOutputFilename()
    {
        $options = $this->process->options;

        $inputBasename = basename($this->process->input->filename ?: '', '.css');
        $outputBasename = $inputBasename;

        if (! empty($options->output_file)) {
            $outputBasename = basename($options->output_file, '.css');
        }

        if ($this->process->input->dir === $this->getOutputDir() && $inputBasename === $outputBasename) {
            $outputBasename .= '.crush';
        }

        return "$outputBasename.css";
    }

    public function getOutputUrl()
    {
        $process = $this->process;
        $options = $process->options;
        $filename = $process->output->filename;

        $url = $process->output->dirUrl . '/' . $filename;

        // Make URL relative if the input path was relative.
        $input_path = new Url($process->input->raw);
        if ($input_path->isRelative) {
            $url = Util::getLinkBetweenPaths(Crush::$config->scriptDir, $process->output->dir) . $filename;
        }

        // Optional query-string timestamp.
        if ($options->versioning !== false) {
            $url .= '?';
            if (isset($process->cacheData[$filename]['datem_sum'])) {
                $url .= $process->cacheData[$filename]['datem_sum'];
            }
            else {
                $url .= time();
            }
        }

        return $url;
    }

    public function validateCache()
    {
        $process = $this->process;
        $options = $process->options;
        $input = $process->input;

        $dir = $this->getOutputDir();
        $filename = $this->getOutputFilename();
        $path = "$dir/$filename";

        if (! file_exists($path)) {
            debug("File '$path' not cached.");

            return false;
        }

        if (! isset($process->cacheData[$filename])) {
            debug('Cached file exists but is not registered.');

            return false;
        }

        $data =& $process->cacheData[$filename];

        // Make stack of file mtimes starting with the input file.
        $file_sums = [$input->mtime];
        foreach ($data['imports'] as $import_file) {

            // Check if this is docroot relative or input dir relative.
            $root = strpos($import_file, '/') === 0 ? $process->docRoot : $input->dir;
            $import_filepath = realpath($root) . "/$import_file";

            if (file_exists($import_filepath)) {
                $file_sums[] = filemtime($import_filepath);
            }
            else {
                // File has been moved, remove old file and skip to compile.
                debug('Recompiling - an import file has been moved.');

                return false;
            }
        }

        $files_changed = $data['datem_sum'] != array_sum($file_sums);
        if ($files_changed) {
            debug('Files have been modified. Recompiling.');
        }

        // Compare runtime options and cached options for differences.
        // Cast because the cached options may be a \stdClass if an IO adapter has been used.
        $options_changed = false;
        $cached_options = (array) $data['options'];
        $active_options = $options->get();
        foreach ($cached_options as $key => &$value) {
            if (isset($active_options[$key]) && $active_options[$key] !== $value) {
                debug('Options have been changed. Recompiling.');
                $options_changed = true;
                break;
            }
        }

        if (! $options_changed && ! $files_changed) {
            debug("Files and options have not been modified, returning cached file.");

            return true;
        }
        else {
            $data['datem_sum'] = array_sum($file_sums);

            return false;
        }
    }

    public function getCacheData()
    {
        $process = $this->process;

        if (file_exists($process->cacheFile) && $process->cacheData) {

            // Already loaded and config file exists in the current directory
            return;
        }

        $cache_data_exists = file_exists($process->cacheFile);
        $cache_data_file_is_writable = $cache_data_exists ? is_writable($process->cacheFile) : false;
        $cache_data = [];

        if (
            $cache_data_exists &&
            $cache_data_file_is_writable &&
            $cache_data = json_decode(file_get_contents($process->cacheFile), true)
        ) {
            // Successfully loaded config file.
            debug('Cache data loaded.');
        }
        else {
            // Config file may exist but not be writable (may not be visible in some ftp situations?)
            if ($cache_data_exists) {
                if (! @unlink($process->cacheFile)) {
                    notice('Could not delete cache data file.');
                }
            }
            else {
                debug('Creating cache data file.');
            }
            Util::filePutContents($process->cacheFile, json_encode([]), __METHOD__);
        }

        return $cache_data;
    }

    public function saveCacheData()
    {
        $process = $this->process;

        debug('Saving config.');

        Util::filePutContents($process->cacheFile, json_encode($process->cacheData, JSON_PRETTY_PRINT), __METHOD__);
    }

    public function write(StringObject $string)
    {
        $process = $this->process;

        $dir = $this->getOutputDir();
        $filename = $this->getOutputFilename();
        $sourcemapFilename = "$filename.map";

        if ($process->sourceMap) {
            $string->append($process->newline . "/*# sourceMappingURL=$sourcemapFilename */");
        }

        if (Util::filePutContents("$dir/$filename", $string, __METHOD__)) {

            if ($process->sourceMap) {
                Util::filePutContents("$dir/$sourcemapFilename",
                    json_encode($process->sourceMap, JSON_PRETTY_PRINT), __METHOD__);
            }

            if ($process->options->stat_dump) {
                $statFile = is_string($process->options->stat_dump) ?
                    $process->options->stat_dump : "$dir/$filename.json";

                $GLOBALS['CSSCRUSH_STAT_FILE'] = $statFile;
                Util::filePutContents($statFile, json_encode(csscrush_stat(), JSON_PRETTY_PRINT), __METHOD__);
            }

            return true;
        }

        return false;
    }
}
