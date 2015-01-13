'use strict';

module.exports = function(grunt) {

  return {
    docs: {
      files: ['**/*.rst'],
      tasks: ['exec:build_docs'],
      options: {
        livereload: true
      }
    }
  }
}