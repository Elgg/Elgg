module.exports = function(grunt) {

  require('load-grunt-config')(grunt);

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
