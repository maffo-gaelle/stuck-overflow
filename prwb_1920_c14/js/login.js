$(function() {
    $("input:text:first").focus();
    validate_login();
});

function validate_login() {
    $("#loginForm").validate({
        rules: {
            UserName: {
                remote: {
                    url: "user/usernameLog_available_service",
                    type: "post",
                    data: {
                        UserName: function() {
                            return $("#UserName").val();
                        }
                    }
                },
                required: true,
            },
            Password: {
                remote: {
                    url: "user/password_available_service",
                    type: "post",
                    data: {
                        UserName: function() {
                            return $("#UserName").val();
                        },
                        Password: function() {
                            return $("#Password").val();
                        }
                    }
                },
                required: true
            }
        },
        messages: {
            UserName: {
                remote: "Cet utilisateur n'existe pas. Veuillez vous inscrire",
                required: "Le nom d'utilisateur est requis",
            },
            Password: {
                remote: "Le mot de passe est érroné",
                required: "Un mot de passe est requis",
            }
        }
    });
}