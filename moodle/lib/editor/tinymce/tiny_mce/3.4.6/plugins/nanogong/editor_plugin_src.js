(function(tinymce) {
    tinymce.create('tinymce.plugins.NanogongPlugin', {
        init : function(ed, url) {
            var cls = 'mceNanogong';

            var edid = ed.id;
            if (edid == 'mce_fullscreen') {
	            edid = ed.settings['fullscreen_editor_id'];
            }
            var itemid = M.editor_tinymce.filepicker_options[edid]['image']['itemid'];

            // Register commands
            ed.addCommand('mceNanogong', function() {
                ed.windowManager.open({
                    file : url + '/nanogong.php?itemid=' + itemid,
                    width : 320,
                    height : 180,
                    inline : 1
                }, {
                    plugin_url : url
                });
            });
            ed.addCommand('mceNanogong_view', function() {
                ed.windowManager.open({
                    file : url + '/nanogong_view.php',
                    width : 320,
                    height : 180,
                    inline : 1
                }, {
                    plugin_url : url
                });
            });

            // Register buttons
            ed.addButton('nanogong', {title : 'NanoGong', image : url + '/img/nanogong.gif', cmd : 'mceNanogong'});

            ed.onInit.add(function() {
                if (ed.theme.onResolveName) {
                    ed.theme.onResolveName.add(function(th, o) {
                        if (o.node.nodeName == 'IMG' && ed.dom.hasClass(o.node, cls))
                            o.name = 'nanogong';
                    });
                }
                if (ed && ed.plugins.contextmenu) {
                    ed.plugins.contextmenu.onContextMenu.add(function(plugin, menu, element) {
                        if (element.nodeName == 'IMG' && ed.dom.hasClass(element, cls)) {
                            menu.removeAll();
                            menu.add({title : 'Show NanoGong Voice', icon : 'nanogong', cmd : 'mceNanogong_view'});
                        }
                    });
                }
            });

            ed.onClick.add(function(ed, e) {
                e = e.target;

                if (e.nodeName === 'IMG' && ed.dom.hasClass(e, cls))
                    ed.selection.select(e);
            });

            ed.onNodeChange.add(function(ed, cm, n) {
                cm.setActive('nanogong', n.nodeName === 'IMG' && ed.dom.hasClass(n, cls));
            });
        },

        getInfo : function() {
            return {
                longname : 'NanoGong',
                author : 'The Gong Project',
                authorurl : 'http://nanogong.ust.hk',
                infourl : 'http://nanogong.ust.hk',
                version : tinymce.majorVersion + "." + tinymce.minorVersion
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('nanogong', tinymce.plugins.NanogongPlugin);
})(tinymce);
