module.exports = function(grunt) {

  require('load-grunt-config')(grunt);

  grunt.initConfig({
	  clean: {
		  docs: ['docs/_build']
	  },
	  connect: {
		  docs: {
			  options: {
				  	hostname: "*",
			  		port: 1919,
		  			base: 'docs/_build',
		  			livereload: true
			  }
		  }
	  },
	  exec: {
		  build_docs: {
			  cmd: 'sphinx-build docs docs/_build'
		  }
	  },
	  open: {
		  docs: {
			  path: 'http://localhost:1919/index.html',
		  }
	  },
	  watch: {
		  docs: {
			  files: ['**/*.rst'],
			  tasks: ['exec:build_docs'],
			  options: {
				  livereload: true
			  }
		  }
	  }
  });
  
  grunt.registerTask('build', [
    'clean:docs',
    'exec:build_docs'
  ]);
  grunt.registerTask('default', [
    'clean:docs',
    'exec:build_docs',
    'connect:docs',
    'open:docs',
    'watch:docs'
  ]);
};
