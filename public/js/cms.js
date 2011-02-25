jQuery(function($){
    var saveUrl = '/content/save',
        publishUrl = '/content/publish',
        editables = $('[class*=editable:]');
    
    editables.addClass('editable');
    
    var editing = null;
    
    editables.click(function(e){
        // Only edit on right-click
        if (!e.shiftKey) return true;
        e.preventDefault();
        var elem = $(this);
        var data = extractEditableProperties(elem.attr('class'));
        console.log(data);
        var form = createForm(data, elem);
        var dialog = $('<div></div>');
        dialog.html(form);
        $(document.body).append(dialog);
        dialog.dialog({
            modal: true,
            width: 600,
            buttons: {
                "Save": function() {
                    alert('saving!');
                    $(this).dialog( "close" );
                },
                "Cancel": function() {
                    $(this).dialog( "close" );                    
                }
            }
        });
    });
    
    var extractEditableProperties = function(className) {
        var classes = className.split(' ');
        var properties = {};
        var regex = /^editable:.*$/;
        for (i in classes) {
            var c = new String(classes[i]);
            if (!c.match(regex)) continue;
            c = c.substring(9);
            var parts = c.split('&');
            for (j in parts) {
                var split = parts[j].split('=');
                properties[split[0]] = split[1];
            }
        }
        return properties;
    };
    
    var createForm = function(data, elem) {
        var form = $('<form></form>');
        for (i in data) {
            var container = $('<div></div>');
            container.append('<label for="content-' + i + '">' + i + '</label><br />');
            var textarea = $('<textarea id="content-'+ i + '" class="editable-field"></textarea>');
            if (i == 'content') {
                textarea.val(elem.html());
            } else {
                textarea.val(elem.attr(i));
            }
            container.append(textarea);;
            form.append(container);
        }
        return form;
    };
});