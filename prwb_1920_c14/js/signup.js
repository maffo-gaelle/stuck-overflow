$(function() {
    $("input:text:first").focus();
    validate_signup();
});

$.validator.addMethod("regex", function(value, element, pattern) {
    if(pattern instanceof Array) {
        for(p of pattern) {
            if(!p.test(value)) {
                return false;
            }
        }
        return true;
    } else {
        return pattern.test(value);
    }
});

function validate_signup() {
    $("#signupForm").validate({
        rules: {
            UserName: {
                remote: {
                    url: "user/username_available_service",
                    type: "post",
                    data: {
                        UserName: function() {
                            return $("#UserName").val();
                        }
                    }
                },
                required: true,
                minlength: 3
            },
            FullName: {
                required: true,
                minlength: 3    
            },
            Password: {
                required: true,
                minlength: 8,
                regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/]
            },
            password_confirm: {
                required: true,
                equalTo: "#Password",
                regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/]
            },
            Email: {
                remote: {
                    url: "user/email_available_service",
                    type: "post",
                    data: {
                        Email: function() {
                            return $("#Email").val();
                        }
                    }
                },
                required: true,
                regex: /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
            }
        },
        messages: {
            UserName: {
                remote: "Cet utilisateur existe déjà.",
                required: "Le nom d'utilisateur est requis",
                minlength: "Le nombre de caractère doit être supérieur à 2",
            }, 
            FullName: {
                required: "Le nom complet d'utilisateur est réquis",
                minlength: "Le nombre de caractère doit être supérieur à 2"
            },
            Password: {
                required: "Le mot de passe est requis",
                minlength: "Le mot de passe doit contenir au moins 8 caractères",
                regex: "Le mot de passe doit contenir au moins un chiffre, une lettre majuscule et un caractère non alphanumérique."
            },
            password_confirm: {
                required: "Veuillez confirmer votre mot de passe",
                equalTo: "Les mots de passe doivent être identiques",
                regex: "Le mot de passe doit contenir au moins un chiffre, une lettre majuscule et un caractère non alphanumérique."
            },
            Email: {
                remote: "Cet email est déjà utilisé",
                required: "L'email de l'utilisateur est requis",
                regex: "Le format de l'email n'est pas valide"
            }
        }
    });
}