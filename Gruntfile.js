/*global module:false*/
/*global require:false*/
/*jshint -W097*/
"use strict";

module.exports = function(grunt) {
 
    // load all grunt tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
 
    grunt.initConfig({
 
        // watch for changes and trigger compass, jshint, uglify and livereload
        watch: {                        
            sass: {
                files: [ 
                    'assets/css/',
                    'assets/css/**'
                ],
                tasks: ['sass:dist']
            },
            sync: {
                files: [
                    'admin/', 
                    'admin/**', 
                    'assets/',
                    'assets/**',                    
                    'includes', 
                    'includes/**', 
                    'i18n', 
                    'i18n/**', 
                    'templates', 
                    'templates/**', 
                    'charitable.php', 
                    'readme.txt', 
                    'README.md'
                ],
                tasks: ['sync:dist']
            }     
        },

        // Sass
        sass: {
            dist: {
                files: {                    
                    'assets/css/charitable-admin-menu.css'  : 'assets/css/scss/charitable-admin-menu.scss', 
                    'assets/css/charitable-admin.css'       : 'assets/css/scss/charitable-admin.scss',
                    'assets/css/charitable.css'             : 'assets/css/scss/charitable.scss'
                }
            }
        },

        // Sync
        sync: {                
            dist: {
                files: [
                    // includes files within path
                    {
                        src: [ 
                            'admin/',
                            'admin/**',
                            'assets/css/*.css',
                            'assets/css/**/*.css',
                            'assets/js/**',
                            'assets/fonts/**',
                            'assets/images/**',
                            'includes',
                            'includes/**',
                            'i18n', 
                            'i18n/**', 
                            'templates', 
                            'templates/**', 
                            'charitable.php',
                            'readme.txt', 
                            'README.md'
                        ], 
                        dest: '../../plugins/charitable'
                    }
                ], 
                verbose: true, 
                updateAndDelete: true
            }
        },
 
        // javascript linting with jshint
        jshint: {
            options: {
                jshintrc: '.jshintrc',
                force: true
            },
            all: [
                'Gruntfile.js'
            ]
        },        

        // uglify to concat, minify, and make source maps
        uglify: {
            dist: {
                files: {
                    'assets/js/charitable-admin.min.js' : 'assets/js/charitable-admin.js'
                }
            }
        },

        // minify CSS
        cssmin: {
            minify: {
                files: {
                    'assets/css/charitable.min.css' : 'assets/css/charitable.css'
                }
            }
        },        

        // make POT file
        makepot: {
            target: {
                options: {
                    cwd: '',                        // Directory of files to internationalize.
                    domainPath: '/i18n/languages',  // Where to save the POT file.                    
                    mainFile: 'charitable.php',     // Main project file.
                    potFilename: 'charitable.pot',  // Name of the POT file.
                    type: 'wp-plugin',              // Type of project (wp-plugin or wp-theme).
                    updateTimestamp: true           // Whether the POT-Creation-Date should be updated without other changes.
                }
            }
        }

    });

    // register task
    // grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.registerTask('default', ['watch']);
    grunt.registerTask('build', ['jshint', 'uglify', 'makepot']);
};