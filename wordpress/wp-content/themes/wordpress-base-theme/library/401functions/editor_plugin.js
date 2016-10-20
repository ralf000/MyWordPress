/**
 * SectionBreak plugin.
 */

(function() {
	var DOM = tinymce.DOM;

	tinymce.create('tinymce.plugins.SectionBreak', {
		init : function(ed, url) {
			var t = this, sectionHTML;
			sectionHTML = '<img src="' + url + '/img/trans.gif" class="mceWPsection mceItemNoResize" title="Section Break" />';

			// Register commands
			ed.addCommand('WP_Section', function() {
				ed.execCommand('mceInsertContent', 0, sectionHTML);
			});

			// Register buttons
			ed.addButton('wp_sectionbreak', {
				title : 'Add Section Break (Alt+Shift+F)',
				image : url + '/img/section.gif',
				cmd : 'WP_Section'
			});

			// Add listeners to handle sectionbreak
			t._handleSectionBreak(ed, url);

			// Add custom shortcuts
			ed.addShortcut('alt+shift+f', 'Add Section Break', 'WP_Section');
		},

		getInfo : function() {
			return {
				longname : 'Section Break Plugin',
				author : 'Devon Beck', 
				authorurl : 'http://401creative.com',
				infourl : 'http://401creative.com',
				version : '1.0'
			};
		},

		_handleSectionBreak : function(ed, url) {
			var sectionHTML;
			
			sectionHTML = '<img src="' + url + '/img/trans.gif" class="mceWPsection mceItemNoResize" title="Section Break" />';

			// Load plugin specific CSS into editor
			ed.onInit.add(function() {
				ed.dom.loadCSS(url + '/css/content.css');
			});

			// Display sectionbreak instead if img in element path
			ed.onPostRender.add(function() {
				if (ed.theme.onResolveName) {
					ed.theme.onResolveName.add(function(th, o) {
						if (o.node.nodeName == 'IMG') {
							if ( ed.dom.hasClass(o.node, 'mceWPsection') )
								o.name = 'wp_sectionbreak';
						}

					});
				}
			});

			// Replace sectionbreak with images
			ed.onBeforeSetContent.add(function(ed, o) {
				o.content = o.content.replace(/<!--section-->/g, sectionHTML);
			});

			// Replace images with sectionbreak
			ed.onPostProcess.add(function(ed, o) {
				if (o.get)
					o.content = o.content.replace(/<img[^>]+>/g, function(im) {
						if (im.indexOf('class="mceWPsection') !== -1)
							im = '<br /><!--section--><br />';

						return im;
					});
			});

			// Set active buttons if user selected sectionbreak
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('wp_sectionbreak', n.nodeName === 'IMG' && ed.dom.hasClass(n, 'mceWPsection'));
			});
			
		}
	});

	// Register plugin
	tinymce.PluginManager.add('sectionbreak', tinymce.plugins.SectionBreak);
})();
