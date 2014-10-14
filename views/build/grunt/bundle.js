module.exports = function(grunt) { 

    var requirejs   = grunt.config('requirejs') || {};
    var clean       = grunt.config('clean') || {};
    var copy        = grunt.config('copy') || {};

    var root        = grunt.option('root');
    var libs        = grunt.option('mainlibs');
    var ext         = require(root + '/tao/views/build/tasks/helpers/extensions')(grunt, root);

    /**
     * Remove bundled and bundling files
     */
    clean.ltitestconsumerbundle = ['output',  root + '/ltiTestConsumer/views/js/controllers.min.js'];
    
    /**
     * Compile tao files into a bundle 
     */
    requirejs.ltitestconsumerbundle = {
        options: {
            baseUrl : '../js',
            dir : 'output',
            mainConfigFile : './config/requirejs.build.js',
            paths : { 'ltiTestConsumer' : root + '/ltiTestConsumer/views/js' },
            modules : [{
                name: 'ltiTestConsumer/controller/routes',
                include : ext.getExtensionsControllers(['ltiTestConsumer']),
                exclude : ['mathJax', 'mediaElement'].concat(libs)
            }]
        }
    };

    /**
     * copy the bundles to the right place
     */
    copy.ltitestconsumerbundle = {
        files: [
            { src: ['output/ltiTestConsumer/controller/routes.js'],  dest: root + '/ltiTestConsumer/views/js/controllers.min.js' },
            { src: ['output/ltiTestConsumer/controller/routes.js.map'],  dest: root + '/ltiTestConsumer/views/js/controllers.min.js.map' }
        ]
    };

    grunt.config('clean', clean);
    grunt.config('requirejs', requirejs);
    grunt.config('copy', copy);

    // bundle task
    grunt.registerTask('ltitestconsumerbundle', ['clean:ltitestconsumerbundle', 'requirejs:ltitestconsumerbundle', 'copy:ltitestconsumerbundle']);
};
