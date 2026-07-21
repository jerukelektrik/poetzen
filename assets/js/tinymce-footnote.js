(function () {
  if (typeof tinymce === 'undefined') return;

  tinymce.PluginManager.add('ss_footnote_plugin', function (editor) {
    editor.addButton('ss_footnote_button', {
      text: 'FN',
      tooltip: 'Tambah Catatan Kaki [fn]',
      icon: false,
      onclick: function () {
        var selected = editor.selection.getContent({ format: 'text' });
        var fnText = prompt('Masukkan isi Catatan Kaki:', selected || '');
        if (fnText !== null && fnText.trim() !== '') {
          editor.insertContent('[fn]' + fnText.trim() + '[/fn]');
        }
      }
    });
  });
})();
