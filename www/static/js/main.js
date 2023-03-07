function validURL(str) {
    let pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
    return !!pattern.test(str);
}

function validEmail(str) {
    let pattern = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    return !!pattern.test(str);
}

async function send_form() {
    let url = document.location.protocol+"//"+document.location.host+"/www/?page=send",
        email = document.getElementById("email").value,
        site = document.getElementById("site").value;

    if(validEmail(email) == false) {
        document.querySelector(".email .hint").style.display = "block";
        return false;
    } else {
        document.querySelector(".email .hint").style.display = "none";
    }

    if(validURL(site) == false) {
        document.querySelector(".site .hint").style.display = "block";
        return false;
    } else {
        document.querySelector(".site .hint").style.display = "none";
    }

    let data = {'name': document.getElementById("name").value,
        'email': email,
        'site': site};
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });
    let result = await response.json();
    if (result.status == 'ok') {
        console.log(result);
        document.querySelector(".success").style.display = "block";
    } else {
        console.log("Ошибка HTTP: " + response.status);
    }
}
