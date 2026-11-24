(function (blocks, blockEditor, components, element, i18n) {
    'use strict';
    var el = element.createElement;
    var __ = i18n.__;
    var labels = {
        available: __('Open for enquiries', 'csp-service-notice'),
        limited: __('Limited availability', 'csp-service-notice'),
        unavailable: __('Currently unavailable', 'csp-service-notice')
    };

    blocks.registerBlockType('code-spanner/service-notice', {
        edit: function (props) {
            var a = props.attributes;
            var validStatus = Object.prototype.hasOwnProperty.call(labels, a.status) ? a.status : 'available';
            var blockProps = blockEditor.useBlockProps({ className: 'csp-notice csp-notice--' + validStatus });
            return el(element.Fragment, null,
                el(blockEditor.InspectorControls, null,
                    el(components.PanelBody, { title: __('Service notice settings', 'csp-service-notice') },
                        el(components.SelectControl, {
                            label: __('Availability', 'csp-service-notice'), value: validStatus,
                            options: Object.keys(labels).map(function (value) { return { value: value, label: labels[value] }; }),
                            onChange: function (value) { props.setAttributes({ status: value }); }
                        }),
                        el(components.TextControl, {
                            label: __('Contact link label', 'csp-service-notice'), value: a.linkLabel,
                            onChange: function (value) { props.setAttributes({ linkLabel: value }); }
                        }),
                        el(components.TextControl, {
                            label: __('Contact link URL', 'csp-service-notice'), value: a.linkUrl,
                            help: __('Use an http, https, mailto or tel URL. Links are escaped on the server.', 'csp-service-notice'),
                            onChange: function (value) { props.setAttributes({ linkUrl: value }); }
                        })
                    )
                ),
                el('div', blockProps,
                    el('span', { className: 'csp-notice__status' }, labels[validStatus]),
                    el(blockEditor.RichText, {
                        tagName: 'h2', className: 'csp-notice__title', value: a.title, allowedFormats: [],
                        placeholder: __('Notice title', 'csp-service-notice'),
                        onChange: function (value) { props.setAttributes({ title: value }); }
                    }),
                    el(blockEditor.RichText, {
                        tagName: 'p', className: 'csp-notice__message', value: a.message, allowedFormats: [],
                        placeholder: __('Write a short update', 'csp-service-notice'),
                        onChange: function (value) { props.setAttributes({ message: value }); }
                    }),
                    a.linkUrl && a.linkLabel ? el('span', { className: 'csp-notice__link' }, a.linkLabel) : null,
                    el('p', { className: 'csp-notice__editor-note' }, __('The contact link is a preview here. Test the real link on the front end.', 'csp-service-notice'))
                )
            );
        },
        save: function () { return null; }
    });
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.i18n);
