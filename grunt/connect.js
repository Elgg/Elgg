'use strict';

module.exports = function(grunt) {

  return {
    docs: {
      options: {
        hostname: "*",
        port: 1919,
        base: 'docs/_build',
        livereload: true
      }
    }
  }
}