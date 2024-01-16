var artworkSection = document.querySelector('#artwork_section')
var artworkImage = document.querySelector('#artwork_image')
var additionalImagesCarousel = document.querySelector(
    '#additional_images_carousel'
)

var imageSlideElements
if (additionalImagesCarousel) {
    imageSlideElements =
        additionalImagesCarousel.querySelectorAll('.thumbnail_slide')
}

var handleImageClick = (element) => {
    Array.from(document.querySelectorAll('.thumbnail_slide')).forEach((e) => {
        e.classList.remove('is_active')
    })
    element.classList.add('is_active')

    artworkImage.src = element.querySelector('img').src

    var image_weight = document.querySelector('#image_weight')
    image_weight && (image_weight.innerHTML = 'TODO')

    var download_link = document.querySelector('#download_image')
    download_link &&
        download_link.setAttribute('href', element.querySelector('img').src),
        download_link.setAttribute('download', element.querySelector('img').src)

    var enlarge_link = document.querySelector('#enlarge_link')
    enlarge_link &&
        enlarge_link.setAttribute('href', element.querySelector('img').src)
}

if (artworkImage && additionalImagesCarousel && imageSlideElements) {
    imageSlideElements.forEach((element) => {
        element.addEventListener('click', (event) => {
            handleImageClick(element)
        })

        element.addEventListener('keypress', (event) => {
            handleImageClick(element)
        })
    })
}

const paginationNumbers = document.getElementById('pagination_numbers')
const paginatedList = document.getElementById('paginated_section')
if (paginatedList) {
    const items = paginatedList.querySelectorAll('.gallery_item')
    const prevButton = document.getElementById('prev_pag_button')
    const nextButton = document.getElementById('next_pag_button')

    const paginationLimit = 10
    let currentPage = 1
    const pageCount = Math.ceil(items.length / paginationLimit)

    const disableButton = (button) => {
        button.setAttribute('disabled', true)
    }

    const enableButton = (button) => {
        button.removeAttribute('disabled')
    }

    const backToTop = () => {
        document.getElementById('top_gallery').scrollIntoView()
    }

    const handlePageButtonsStatus = () => {
        if (currentPage === 1) {
            disableButton(prevButton)
        } else {
            enableButton(prevButton)
        }

        if (pageCount === currentPage) {
            disableButton(nextButton)
        } else {
            enableButton(nextButton)
        }
    }

    const handleActivePageNumber = () => {
        document.querySelectorAll('.pagination_number').forEach((button) => {
            button.removeAttribute('disabled')
            const pageIndex = Number(button.getAttribute('page-index'))
            if (pageIndex == currentPage) {
                button.setAttribute('disabled', true)
            }
        })
    }

    const appendPageNumber = (index) => {
        const pageNumber = document.createElement('button')
        pageNumber.className =
            'button_reverse pagination_controls pagination_number'
        pageNumber.innerHTML = index
        pageNumber.setAttribute('page-index', index)
        pageNumber.setAttribute('aria-label', 'Pagina ' + index)

        paginationNumbers.appendChild(pageNumber)
    }

    const getPaginationNumbers = () => {
        for (let i = 1; i <= pageCount; i++) {
            appendPageNumber(i)
        }
    }

    const setCurrentPage = (pageNum, scroll = true) => {
        currentPage = pageNum

        handleActivePageNumber()
        handlePageButtonsStatus()

        const prevRange = (pageNum - 1) * paginationLimit
        const currRange = pageNum * paginationLimit

        items.forEach((item, index) => {
            item.classList.add('hidden')
            if (index >= prevRange && index < currRange) {
                item.classList.remove('hidden')
            }
        })

        scroll && backToTop()
    }

    getPaginationNumbers()
    setCurrentPage(1, false)

    prevButton.addEventListener('click', () => {
        setCurrentPage(currentPage - 1)
    })

    nextButton.addEventListener('click', () => {
        setCurrentPage(currentPage + 1)
    })

    document.querySelectorAll('.pagination_number').forEach((button) => {
        const pageIndex = Number(button.getAttribute('page-index'))

        if (pageIndex) {
            button.addEventListener('click', () => {
                setCurrentPage(pageIndex)
            })
        }
    })

    const backToTopButton = document.getElementById('back_to_top')

    backToTopButton.addEventListener('click', () => {
        backToTop()
    })
}

const startDate = document.getElementById('start_date')
const startDateFuture = document.getElementById('start_date_future')
const endDate = document.getElementById('end_date')
const endDatePast = document.getElementById('end_date_past')

if ((startDate || startDateFuture) && (endDate || endDatePast)) {
    today = new Date()

    if (startDate) {
        if (endDate) {
            endDate.min = today.toLocaleDateString('fr-ca')
            var max_limit = endDate.value
            startDate.max = max_limit
            startDate.addEventListener('input', () => {
                var min_limit = startDate.value
                endDate.min = min_limit
            })
        } else {
            endDatePast.max = today.toLocaleDateString('fr-ca')
            var max_limit = endDatePast.value
            startDate.max = max_limit
            startDate.addEventListener('input', () => {
                var min_limit = startDate.value
                endDatePast.min = min_limit
            })
        }
    }

    if (startDateFuture) {
        if (endDate) {
            endDate.min = today.toLocaleDateString('fr-ca')
            startDateFuture.min = today.toLocaleDateString('fr-ca')
            var max_limit = endDate.value
            startDateFuture.max = max_limit

            startDateFuture.addEventListener('input', () => {
                var min_limit = startDateFuture.value
                endDate.min = min_limit
            })
        }
    }
    if (endDate) {
        endDate.addEventListener('input', () => {
            var max_limit = endDate.value
            if (startDate) {
                startDate.max = max_limit
            }
            if (startDateFuture) {
                startDateFuture.max = max_limit
            }
        })
    } else {
        endDatePast.addEventListener('input', () => {
            var max_limit = endDate.value
            if (startDate) {
                startDate.max = max_limit
            }
            if (startDateFuture) {
                startDateFuture.max = max_limit
            }
        })
    }
}

const pastDate = document.getElementById('birthdate')
pastDate && (pastDate.max = new Date().toLocaleDateString('fr-ca'))

const additionalImagesInput = document.getElementById('additional-images-input')
if (additionalImagesInput) {
    additionalImagesInput.addEventListener('change', (event) => {
        const uploadImgContainer = document.querySelector(
            '#additional-images-viewer'
        )
        uploadImgContainer.innerHTML = ''

        if (event.target.files) {
            files = event.target.files
            const fragment = document.createDocumentFragment()

            for (let i = 0; i < files.length; i++) {
                const imgContainer = document.createElement('div')
                imgContainer.className = 'uploaded_image'

                const img = document.createElement('img')
                img.src = URL.createObjectURL(files[i])
                img.setAttribute('add-img-id', i)
                img.alt = files[i].name
                files[i].imgId = i

                const removeBtn = document.createElement('button')
                removeBtn.type = 'button'
                removeBtn.className = 'remove_btn'
                removeBtn.ariaLabel = "Elimina l'immagine"
                removeBtn.addEventListener('click', (e) => {
                    const removedImgContainer = e.target.parentNode
                    removedImgContainer.remove()

                    updateFileList(i)
                })

                imgContainer.appendChild(img)
                imgContainer.appendChild(removeBtn)
                fragment.appendChild(imgContainer)
            }

            uploadImgContainer.appendChild(fragment)
        }
    })

    const updateFileList = (idRemoved) => {
        const dt = new DataTransfer()
        const uploadInput = document.getElementById('additional-images-input')
        const updatedFiles = uploadInput.files

        for (let i = 0; i < updatedFiles.length; i++) {
            if (idRemoved != updatedFiles[i].imgId)
                dt.items.add(updatedFiles[i])
        }

        uploadInput.files = dt.files
    }
}

const mainImageInput = document.getElementById('main-image-input')
if (mainImageInput) {
    mainImageInput.addEventListener('change', (event) => {
        const mainImageContainer = document.querySelector('#main-image-viewer')
        mainImageContainer.innerHTML = ''

        if (event.target.files) {
            file = event.target.files[0]

            const imgContainer = document.createElement('div')
            imgContainer.className = 'uploaded_image'

            const img = document.createElement('img')
            img.src = URL.createObjectURL(file)
            img.alt = file.name

            imgContainer.appendChild(img)
            mainImageContainer.appendChild(imgContainer)
        }
    })
}

const profileImageInput = document.getElementById('profile-image-input')
if (profileImageInput) {
    profileImageInput.addEventListener('change', (event) => {
        const profileImage = document.querySelector('#profile-image')

        if (event.target.files && event.target.files.length > 0) {
            file = event.target.files[0]

            profileImage.src = URL.createObjectURL(file)
            profileImage.alt = file.name
        } else {
            profileImage.src = '../assets/images/default_user.svg'
        }
    })
}

const confirmAction = (question) => {
    var confirm = window.confirm(question)
    return confirm
}

const logoutButton = document.getElementById('logout_button')
logoutButton &&
    logoutButton.addEventListener('click', (event) => {
        confirmAction('Vuoi veramente uscire?') &&
            window.location &&
            (window.location = '../php/logout.php')
    })

const bookForm = document.getElementById('artshow_prenotation')
bookForm &&
    bookForm.addEventListener('submit', (event) => {
        !confirmAction('Vuoi iscriverti alla mostra?') && event.preventDefault()
    })

const cancelBookForm = document.getElementById('artshow_cancel_prenotation')
cancelBookForm &&
    cancelBookForm.addEventListener('submit', (event) => {
        !confirmAction("Vuoi veramente annullare l'iscrizione?") &&
            event.preventDefault()
    })

// test su validità di input

function rulePassword(password) {
    var re = /^[a-zA-Z0-9!?$&@%]+$/;     //qui ho messo che i caratteri speciali ammessi sono !?$&@%
    return re.test(password);
}

function ruleUsername(user) {
    var re = /^[a-z0-9]+(?:[._][a-z0-9]+)*$/;            //caratteri speciali ammessi solo . e _
    return re.test(user);
}

function ruleName(name) {                               //Vale anche per lastname
    var re = /^[A-Z][a-zàèìòùÀÈÌÒÙáéíóúÁÉÍÓÚ]+(?: [A-Z][a-zàèìòùÀÈÌÒÙáéíóúÁÉÍÓÚ]+)?$/;          //Controlla che le iniziali siano in maiuscolo e permetta la presenza di uno spazio e nome successivo (tipo 'De Santis')
    return re.test(name);
}

function ruleBirthPlace(place) {
    var re = /^(?:[A-Z][a-z]+(?: [A-Z][a-z]+)?(?: [A-Z][a-z]+)*)?$/;        //Ogni parola del paese deve iniziare con la maiuscola
    return re.test(place);
}

function ruleComment(comment) {                                             //per descrizione, esperienze e biografia
    var re = /^[a-zA-ZàèìòùÀÈÌÒÙáéíóúÁÉÍÓÚ0-9.,;:'"()!? \n]+$/;
    return re.test(comment);
}

function ruleTitle(title) {
    var re = /^[a-zA-ZàèìòùÀÈÌÒÙáéíóúÁÉÍÓÚ0-9.,;:'"()!? ]+$/;
    return re.test(title);
}

function displayError(container, message) {
    if (!container) {
        container = document.createElement("p");
        container.classList.add("error_message");
        x.parentElement.append(container);
    }
    container.textContent = message;
}

function clearError(container) {
    if (container) {
        container.remove();
    }
}

function checkTitle(id) {
    let x = document.getElementById(id);
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement("p");
        node.classList.add("error_message");
        x.parentElement.append(node);
    }
    const errorContainer = x.parentElement.querySelector('.error_message');
    if (x.value == "") {
        displayError(errorContainer, "Per favore, inserisci una titolo all'opera.");
        return false;
    } else if (!ruleComment(x.value)) {
        displayError(errorContainer, "Attenzione, il titolo dell'opera può contenere solo lettere, lettere accentate, numeri e segni di punteggiatura base (. , ; : ! ? ' \" (  ) ! ? ).");
        return false;
    } else if (x.value.length > 100) {
        displayError(errorContainer, "Attenzione, il titolo della tua opera non può superare i 100 caratteri.");
        return false;
    } else {
        clearError(errorContainer);
        return true;
    }
}

function checkDescription(id) {
    let x = document.getElementById(id);
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement("p");
        node.classList.add("error_message");
        x.parentElement.append(node);
    }
    const errorContainer = x.parentElement.querySelector('.error_message');
    if (x.value == "") {
        displayError(errorContainer, "Per favore, inserisci una descrizione dell'opera. Per dei consigli su come comporre una buona descrizione, puoi utilizzare le indicazioni sopra riportate.");
        return false;
    } else if (!ruleComment(x.value)) {
        displayError(errorContainer, "Attenzione, la descrizione può contenere solo lettere, lettere accentate, numeri e segni di punteggiatura base (. , ; : ! ? ' \" (  ) ! ? ).");
        return false;
    } else if (x.value.length > 1000) {
        displayError(errorContainer, "Attenzione, la descrizione non deve superare i 1000 caratteri.");
        return false;
    } else {
        clearError(errorContainer);
        return true;
    }
}


function checkArtshowTitle(id) {
    let x = document.getElementById(id);
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement("p");
        node.classList.add("error_message");
        x.parentElement.append(node);
    }
    const errorContainer = x.parentElement.querySelector('.error_message');
    if (x.value == "") {
        displayError(errorContainer, "Per favore, inserisci una titolo alla mostra.");
        return false;
    } else if (!ruleComment(x.value)) {
        displayError(errorContainer, "Attenzione, il titolo della mostra può contenere solo lettere, lettere accentate, numeri e segni di punteggiatura base (. , ; : ! ? ' \" (  ) ! ? ).");
        return false;
    } else if (x.value.length > 100) {
        displayError(errorContainer, "Attenzione, il titolo della mostra non può superare i 100 caratteri.");
        return false;
    } else {
        clearError(errorContainer);
        return true;
    }
}

function checkArtshowDescription(id) {
    let x = document.getElementById(id);
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement("p");
        node.classList.add("error_message");
        x.parentElement.append(node);
    }
    const errorContainer = x.parentElement.querySelector('.error_message');
    if (x.value == "") {
        displayError(errorContainer, "Per favore, inserisci una descrizione della mostra.");
        return false;
    } else if (!ruleComment(x.value)) {
        displayError(errorContainer, "Attenzione, la descrizione può contenere solo lettere, lettere accentate, numeri e segni di punteggiatura base (. , ; : ! ? ' \" (  ) ! ? ).");
        return false;
    } else if (x.value.length > 1000) {
        displayError(errorContainer, "Attenzione, la descrizione non deve superare i 1000 caratteri.");
        return false;
    } else {
        clearError(errorContainer);
        return true;
    }
}

function checkUsername(id) {
    let x = document.getElementById(id);
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement("p");
        node.classList.add("error_message");
        x.parentElement.append(node);
    }
    const errorContainer = x.parentElement.querySelector('.error_message');
    if (x.value == "") {
        displayError(errorContainer, "Per favore, inserisci lo username che desideri utilizzare.");
        return false;
    } else if (!ruleUsername(x.value)) {
        displayError(errorContainer, "Attenzione, lo username può contenere solo lettere minuscole e numeri. Sono ammessi punti e underscore all'interno.");
        return false;
    } else if (x.value.length > 30) {
        displayError(errorContainer, "Attenzione, lo username non deve superare i 30 caratteri.");
        return false;
    } else if (x.value.length < 4) {
        displayError(errorContainer, "Attenzione, lo username deve contenere almeno 4 caratteri.");
        return false;
    } else {
        clearError(errorContainer);
        return true;
    }
}

function checkPassword(id) {
    let x = document.getElementById(id);
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement("p");
        node.classList.add("error_message");
        x.parentElement.append(node);
    }
    const errorContainer = x.parentElement.querySelector('.error_message');
    if (x.value == "") {
        displayError(errorContainer, "Per favore, inserisci la password che desideri utilizzare.");
        return false;
    } else if (!rulePassword(x.value)) {
        displayError(errorContainer, "Attenzione, la password può contenere lettere non accentate e numeri. Sono ammessi i caratteri speciali !?$&@%.");
        return false;
    } else if (x.value.length > 30) {
        displayError(errorContainer, "Attenzione, la password non deve superare i 255 caratteri.");
        return false;
    } else if (x.value.length < 4) {
        displayError(errorContainer, "Attenzione, la password deve contenere almeno 4 caratteri.");
        return false;
    } else {
        clearError(errorContainer);
        return true;
    }
}

function checkName(id) {
    let x = document.getElementById(id);
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement("p");
        node.classList.add("error_message");
        x.parentElement.append(node);
    }
    const errorContainer = x.parentElement.querySelector('.error_message');
    if (x.value == "") {
        displayError(errorContainer, "Per favore, inserisci il tuo nome.");
        return false;
    } else if (!ruleName(x.value)) {
        displayError(errorContainer, "Per favore, assicurati di aver inserito non più di due nomi, con le iniziali maiuscole e formati da almeno 2 caratteri. Non superare i 30 caratteri totali.");
        return false;
    } else if (x.value.length > 30) {
        displayError(errorContainer, "Attenzione, non puoi inserire un nome che supera i 30 caratteri.");
        return false;
    } else if (x.value.length < 2) {
        displayError(errorContainer, "Attenzione, il tuo nome deve contenere almeno 2 caratteri.");
        return false;
    } else {
        clearError(errorContainer);
        return true;
    }
}

function checkUsernameLogin(id) {
    let x = document.getElementById(id);
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement("p");
        node.classList.add("error_message");
        x.parentElement.append(node);
    }
    const errorContainer = x.parentElement.querySelector('.error_message');
    if (x.value == "") {
        displayError(errorContainer, "Per favore, inserisci il tuo username.");
        return false;
    } else if (!ruleUsername(x.value)) {
        displayError(errorContainer, "Sicuro sia il tuo username? Ricorda che può contenere solo lettere minuscole e numeri. Sono ammessi punti e underscore all'interno.");
        return false;
    } else if (x.value.length > 30) {
        displayError(errorContainer, "Sicuro sia il tuo username? Ricorda che non deve superare i 30 caratteri.");
        return false;
    } else if (x.value.length < 4) {
        displayError(errorContainer, "Sicuro sia il tuo username? Ricorda che deve contenere almeno 4 caratteri.");
        return false;
    } else {
        clearError(errorContainer);
        return true;
    }
}

function checkPasswordLogin(id) {
    let x = document.getElementById(id);
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement("p");
        node.classList.add("error_message");
        x.parentElement.append(node);
    }
    const errorContainer = x.parentElement.querySelector('.error_message');
    if (x.value == "") {
        displayError(errorContainer, "Per favore, inserisci la tua password.");
        return false;
    } else if (!rulePassword(x.value)) {
        displayError(errorContainer, "Sicuro sia la tua password? Ricorda che può contenere lettere non accentate e numeri. Sono ammessi i caratteri speciali !?$&@%.");
        return false;
    } else if (x.value.length > 30) {
        displayError(errorContainer, "Sicuro sia la tua password? Ricorda che non deve superare i 255 caratteri.");
        return false;
    } else if (x.value.length < 4) {
        displayError(errorContainer, "Sicuro sia la tua password? Ricorda che deve contenere almeno 4 caratteri.");
        return false;
    } else {
        clearError(errorContainer);
        return true;
    }
}
