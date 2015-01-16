'use strict';

module.exports = function(grunt) {

  return {
    build_docs: {
      cmd: 'sphinx-build docs docs/_build'
    }
  }
}