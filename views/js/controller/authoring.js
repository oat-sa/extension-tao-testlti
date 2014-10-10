/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2014 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 *
 */
define(['jquery', 'i18n', 'helpers', 'ui/feedback'], function($, __, helpers, feedback) {
 
    return {
        start : function(){
            var $container = $('#lti-test-authoring');
            $('.form-submitter', $container).off('click').on('click', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                var toSend = $('form', $container).serialize();
                $.ajax({
                    url: helpers._url('save', 'Authoring', 'ltiTestConsumer'),
                    type: "POST",
                    data: toSend,
                    dataType: 'json',
                    success: function(response) {
                        if (response.saved) {
                            feedback().success(__('Selection saved successfully'));
                        }
                    }
                 });
            });
        }
    };
});
