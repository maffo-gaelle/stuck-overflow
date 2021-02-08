$(function() {
    $("input:text:last").focus();
    validate_tag();
});

function validate_tag() {
    //console.log('hello')
    $("#TagForm").validate({
        rules: {
            TagName: {
                remote: {
                    url: "tag/tag_available_service",
                    type: "post",
                    data: {
                        TagName: function() {
                            return $("#TagName").val();
                        }
                    }
                },
                required: true
            }
        },
        messages: {
            TagName: {
                remote: "Ce Tag existe déjà",
                required: "Le nom du tag est requis"
            }
        }
    })
}