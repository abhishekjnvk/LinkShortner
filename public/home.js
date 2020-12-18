jQuery(document).ready(function($) {

    const forms = document.querySelectorAll('.requires-validation')
    Array.from(forms)
        .forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    console.log("Form is not valid")
                    event.preventDefault()
                    event.stopPropagation()
                } else {
                    event.preventDefault()
                    event.stopPropagation()
                    $("#btn-submit").attr("disabled", true);
                    $("#alert_box").empty();
                    submitForm();
                }

                form.classList.add('was-validated')
            }, false)
        })


    $("#analytics_enabled").click(function() {
        $("#password_div").show(200);
        $("#analytics_password").attr("required", true);
    });

    $("#analytics_disabled").click(function() {
        $("#password_div").hide(200);
        $("#analytics_password").attr("required", false);
    });

    $("#advance_options").click(function() {
        if ($("#advance_options").is(":checked")) {
            $("#advance_options_div").show(500);
        } else {
            $("#advance_options_div").hide(500);
        }
    });

})

async function submitForm() {
    var link_address = $("#link_address").val();
    var link_expiry = $("#link_expiry").val();
    var analytics_password = $("#analytics_password").val();
    var title = $("#link_title").val();
    
    try {
        let headersList = {
            "Accept": "*/*",
            "Content-Type": "application/json"
        }

        let bodyContent = JSON.stringify({
            "url": link_address,
            "expiry": link_expiry,
            "analytics_password": analytics_password,
            "title": title
        });
        let response = await fetch(`${base_url}API/Shorten`, {
            method: "POST",
            body: bodyContent,
            headers: headersList
        });

        let data = await response.json();

        if (data.status) {
            console.log(data)
            $("#shortener_form").hide(500);
            $("#alert_box").html(
                `<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Status:  ${data.message}</strong><br/><br/>
                            <b>Shorten Url: </b><a href="${data.data.link}" target="_blank" class="alert-link">${data.data.link}</a> <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('${data.data.link}')">Copy</button><br/><br/>
                            ${data.data.analytics_link?`
                            <strong>Analytics URL: </strong> <a href="${data.data.analytics_link}" target="_blank" class="alert-link">${data.data.analytics_link}</a>
                            <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('${data.data.analytics_link}')">Copy</button><br/><br/>
                            <strong>Analytics Password: ${analytics_password}</strong>`:""}
                            <br/><br/><br/><br/><br/>

                            <a href="${base_url}" class="btn btn-sm btn-default mt-2">Shorten Another URL</a>
                        </div>`
            );
        } else {
            $("#btn-submit").attr("disabled", false);
            $("#alert_box").html(
                `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> ${data.message}<br/><br/>
                            <a href="${base_url}" class="btn btn-sm btn-outline-primary mt-2">Shorten Another URL</a>
                        </div>`
            );
        }
    } catch (e) {
        $("#btn-submit").attr("disabled", false);
        $("#alert_box").html(
            `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> ${e.statusText}<br/><br/>
                            <a href="${base_url}" class="btn btn-sm btn-outline-primary mt-2">Shorten Another URL</a>
                    </div>`
        );

    }
}


function copyToClipboard(text) {
    var dummy = document.createElement("textarea");
    document.body.appendChild(dummy);
    dummy.value = text;
    dummy.select();
    document.execCommand("copy");
    document.body.removeChild(dummy);
    alert("Copied to clipboard:> " + text);
}