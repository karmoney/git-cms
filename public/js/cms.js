jQuery(function($){
    var saveUrl = '/offers/save',
        publishUrl = '/offers/publish',
        shareUrl = '/offers/share/',
        editables = $('[class*=editable:]'),
        shim = $('<div class="cms-shim"></div>');
    
    shim.css({
        backgroundColor: 'black',
        opacity: 0.5,
        position: 'fixed',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        zIndex: 10000
    });
    shim.hide(0);
    $(document.body).append(shim);
    
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
                        }
                    });
                },
                "Cancel": function() {
                    $(this).dialog( "close" );                    
                }
            }
        });
    });
    
    var publish = function()
    {
        shim.show(0);
        $.ajax({
            url: publishUrl,
            type: 'POST',
            error: function (r) {
                alert('There was an error while publishing: ' + r.responseText);
                shim.hide(0);
            },
            success: function(r) {
                alert('Your content has been published!');
                shim.hide(0);
            }
        });
    };
    
    var publishButton = $('<button>Publish!</button>');
    publishButton.css({
        position: 'fixed',
        right: '10px',
        bottom: '10px',
        zIndex: 1000
    });
    $(document.body).append(publishButton);
    publishButton.click(function(){
        publish();
    });
    
    var shareButton = $('<button>Share</button>');
    shareButton.css({
        position: 'fixed',
        right: '200px',
        bottom: '10px',
        zIndex: 1000
    });
    $(document.body).append(shareButton);
    shareButton.click(function(){
        shim.show(0);
        $.ajax({
            url: shareUrl + '?name=' + $('#name').val(),
            type: 'GET',
            error: function (r) {
                alert('There was an error while sharing: ' + r.responseText);
                shim.hide(0);
            },
            success: function(r) {
                alert('Your content has been shared!');
                shim.hide(0);
            }
        });
    });
    var shareInput = $('<input type="text" name="user" id="name" value="share me"/>');
    shareInput.css({
        position: 'fixed',
        right: '260px',
        bottom: '5px',
        zIndex: 1000
    });
    $(document.body).append(shareInput);
    
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
                input.val(elem.html().trim());
            } else {
                var input = $('<input type="text" id="content-'+ i + '" class="editable-field"/>');
                input.val(elem.attr(i).trim());
            }
            container.append(input);
            form.append(container);
        }
        return form;
    };
});

String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}