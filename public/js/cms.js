jQuery(function($){
    var saveUrl = '/offers/save',
        publishUrl = '/offers/publish',
        editables = $('[class*=editable:]'),
        shim = $('<div class="cms-shim"></div>');
    
    shim.css({
        backgroundColor: 'black',
        opacity: 0.5,
        position: 'static',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        zIndex: 10000
    });
    shim.hide(0);
    $(document.body).append(shim);
    shim.show(0);
    
    editables.addClass('editable');
    
    var editing = null;
    
    editables.click(function(e){
        // Only edit on right-click
        if (!e.shiftKey) return true;
        e.preventDefault();
        var elem = $(this);
        var data = extractEditableProperties(elem.attr('class'));
        var form = createForm(data, elem);
        var dialog = $('<div></div>');
        dialog.html(form);
        $(document.body).append(dialog);
        dialog.dialog({
            modal: true,
            width: 600,
            buttons: {
                "Save": function() {
                    shim.show(0);
                    var formData = form.serialize();
                    console.log(formData);
                    $.ajax({
                        url: saveUrl,
                        data: formData,
                        type: 'POST',
                        error: function (r) {
                            alert('There was an error while saving: ' + r.responseText);
                            shim.hide(0);
                        },
                        success: function(r) {
                            dialog.dialog("close");
                            alert('Your content has been saved!');
                            shim.hide(0);
                            updateContentFromForm(form, elem);
                            //publish();
                        }
                    });
                },
                "Cancel": function() {
                    $(this).dialog( "close" );                    
                }
            }
        });
    });
    
    var funciton = publish()
    {
        $.ajax({
            url: publishUrl,
            type: 'POST',
            error: function (r) {
                alert('There was an error while saving: ' + r.responseText);
                shim.hide(0);
            },
            success: function(r) {
                dialog.dialog("close");
                alert('Your content has been saved!');
                shim.hide(0);
                updateContentFromForm(form, elem);
                publish();
            }
        });
    };
    
    var updateContentFromForm = function(form, elem) {
        form.find(':input').each(function(i, s) {
            var input = $(s);
            var type = input.attr('id').replace('content-', '');
            if (type == 'content') {
                elem.html(input.val());
            } else {
                elem.attr(type, input.val());
            }
        });
    };
    
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
        var form = $('<form method="POST" action="' + saveUrl + '"></form>');
        for (i in data) {
            var container = $('<div></div>');
            container.append('<label for="content-' + i + '">' + i + '</label><br />');
            var name = 'data[' + data[i].split('/').join('][') + ']';
            if (i == 'content') {
                var input = $('<textarea id="content-'+ i + '" class="editable-field" name="' + name + '"></textarea>');
                input.val(elem.html());
            } else {
                var input = $('<input type="text" id="content-'+ i + '" class="editable-field"/>');
                input.val(elem.attr(i));
            }
            container.append(input);
            form.append(container);
        }
        return form;
    };
});