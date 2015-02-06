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
            sass_admin: {
                files: [ 'admin/assets/css/scss/*.{scss,sass}' ],
                tasks: ['sass:dist_admin']
            },
            sass_public: {
                files: [ 'public/assets/css/scss/*.{scss,sass}' ],
                tasks: ['sass:dist_public']
            },
            sync: {
                files: [
                    'admin/', 
                    'admin/**', 
                    '!admin/assets/css/scss', 
                    '!admin/assets/css/scss/**',
                    'includes', 
                    'includes/**', 
                    'i18n', 
                    'i18n/**', 
                    'public', 
                    'public/**', 
                    '!public/assets/css/scss', 
                    '!public/assets/css/scss/**', 
                    'charitable.php'
                ],
                tasks: ['sync:dist']
            }     
        },

        // Sass
        sass: {
            dist_admin: {
                files: {
                    'admin/assets/css/charitable-admin-menu.css' : 'admin/assets/css/scss/charitable-admin-menu.scss', 
                    'admin/assets/css/charitable-admin.css' : 'admin/assets/css/scss/charitable-admin.scss'
                }
            }, 
            dist_public: {
                files: {
                    'public/assets/css/charitable.css' : 'public/assets/css/scss/charitable.scss'
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
                            '!admin/assets/scss', 
                            '!admin/assets/scss/**',
                            'includes', 
                            'includes/**', 
                            'i18n', 
                            'i18n/**', 
                            'public', 
                            'public/**', 
                            '!public/assets/compass', 
                            '!public/assets/compass/**', 
                            'charitable.php'                                
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
                    'admin/assets/js/charitable-admin.min.js': 'admin/assets/js/charitable-admin.js'
                }
            }
        },

        // minify CSS
        cssmin: {
            minify: {
                files: {
                    'public/assets/css/charitable.min.css' : [ 
                        'public/assets/css/charitable.css'
                    ]
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