<?php

namespace wZm\CustomTopbar\Actions;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SaveLogoAction
{
    /**
     * Save assets
     *
     * @param Request $request Request
     * @return ResponseBuilder
     */
    public function __invoke(\Elgg\Request $request)
    {

        $assets = [
            'logo',
        ];

        foreach ($assets as $asset) {
            $upload = elgg_get_uploaded_file($asset);

            if (empty($upload)) {
                continue;
            }

            if (!$upload instanceof UploadedFile || !$upload->isValid()) {
                continue;
            }

            if ($upload) {
                $target = elgg_get_data_path() . 'assets/' . $asset . '.png';
                if (file_exists($target)) {
                    unlink($target);
                }

                try {
                    $upload->move(elgg_get_data_path() . 'assets/', $asset . '.png');
                    elgg_invalidate_caches();
                    elgg_clear_caches();
                } catch (\Exception $ex) {
                    return false;
                }
            }
        }

        return elgg_ok_response('', elgg_echo('admin:topbar:logo:success'));
    }
}
