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
    const today = new Date().toLocaleDateString('fr-ca')

    if (startDate) {
        startDate.max = today

        if (endDate) {
            endDate.min = today
            startDate.addEventListener('input', () => {
                endDate.min = startDate.value
            })
        } else {
            endDatePast.max = today
            startDate.addEventListener('input', () => {
                endDatePast.min = startDate.value
            })
        }
    }

    if (startDateFuture) {
        startDateFuture.min = today

        if (endDate) {
            endDate.min = today
            startDateFuture.addEventListener('input', () => {
                endDate.min = startDateFuture.value
            })
        }
    }

    if (endDate) {
        endDate.addEventListener('input', () => {
            if (startDate) {
                startDate.max = endDate.value
            }
            if (startDateFuture) {
                startDateFuture.max = endDate.value
            }
        })
    } else {
        endDatePast.addEventListener('input', () => {
            if (startDate) {
                startDate.max = endDatePast.value
            }
            if (startDateFuture) {
                startDateFuture.max = endDatePast.value
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

const disableMainImage = document.getElementById('disable_main_image')
if (disableMainImage) {
    disableMainImage.addEventListener('change', () => {
        if (disableMainImage.checked && mainImageInput) {
            mainImageInput.value = ''
            const mainImageContainer =
                document.querySelector('#main-image-viewer')
            mainImageContainer.innerHTML = ''
        }
        mainImageInput &&
            ((mainImageInput.disabled = disableMainImage.checked),
            (mainImageInput.required = !disableMainImage.checked))
    })
}

const disableAdditionalImages = document.getElementById(
    'disable_additional_images'
)
if (disableAdditionalImages) {
    disableAdditionalImages.addEventListener('change', () => {
        if (disableAdditionalImages.checked && additionalImagesInput) {
            additionalImagesInput.value = ''
            const uploadImgContainer = document.querySelector(
                '#additional-images-viewer'
            )
            uploadImgContainer.innerHTML = ''
        }
        additionalImagesInput &&
            (additionalImagesInput.disabled = disableAdditionalImages.checked)
    })
}

const profileImageInput = document.getElementById('profile-image-input')
const profileImage = document.getElementById('profile-image')
const profileImageSrc = profileImage ? profileImage.src : null
if (profileImageInput) {
    profileImageInput.addEventListener('change', (event) => {
        if (event.target.files && event.target.files.length > 0) {
            file = event.target.files[0]

            profileImage.src = URL.createObjectURL(file)
            profileImage.alt = file.name
        } else {
            profileImage.src = profileImageSrc
                ? profileImageSrc
                : '../assets/images/default_user.svg'
        }
    })
}

const removeProfileImage = document.getElementById('remove_profile_image')
if (removeProfileImage) {
    removeProfileImage.addEventListener('change', () => {
        if (removeProfileImage.checked && profileImageInput) {
            profileImageInput.value = ''
        }
        profileImage.src = !removeProfileImage.checked
            ? profileImageSrc
            : '../assets/images/default_user.svg'
        profileImageInput &&
            (profileImageInput.disabled = removeProfileImage.checked)
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

const deleteArtwork = document.getElementById('delete_artwork')
deleteArtwork &&
    deleteArtwork.addEventListener('submit', (event) => {
        !confirmAction("Vuoi veramente eliminare l'opera?") &&
            event.preventDefault()
    })

const deleteArtshow = document.getElementById('delete_artshow')
deleteArtshow &&
    deleteArtshow.addEventListener('submit', (event) => {
        !confirmAction('Vuoi veramente eliminare la mostra?') &&
            event.preventDefault()
    })

// test su validità di input

function rulePassword(password) {
    var re = /^[a-zA-Z0-9!?$&@%]+$/ //qui ho messo che i caratteri speciali ammessi sono !?$&@%
    return re.test(password)
}

function ruleUsername(user) {
    var re = /^[a-zA-Z0-9]+(?:[._-][a-zA-Z0-9]+)*$/ //caratteri speciali ammessi solo . _ -
    return re.test(user)
}

function ruleName(name) {
    //Vale anche per lastname
    var re =
        /^[A-Z][a-zàèìòùÀÈÌÒÙáéíóúÁÉÍÓÚ]+(?: [A-Z][a-zàèìòùÀÈÌÒÙáéíóúÁÉÍÓÚ]+)?$/ //Controlla che le iniziali siano in maiuscolo e permetta la presenza di uno spazio e nome successivo (tipo 'De Santis')
    return re.test(name)
}

function ruleBirthPlace(place) {
    var re =
        /^(?:[A-ZÁÉÍÓÚÀÈÌÒÙ][a-zàèìòùáéíóú]+(?: [A-ZÁÉÍÓÚÀÈÌÒÙ][a-zàèìòùáéíóú]+)?(?: [A-ZÁÉÍÓÚÀÈÌÒÙ][a-zàèìòùáéíóú]+)*)?$/ //Ogni parola del paese deve iniziare con la maiuscola
    return re.test(place)
}

function ruleComment(comment) {
    //per descrizione, esperienze e biografia
    var re = /^[a-zA-ZàèìòùÀÈÌÒÙáéíóúÁÉÍÓÚ0-9.,;:_'"()!? \n-]+$/
    return re.test(comment)
}

function ruleTitle(title) {
    var re = /^[a-zA-ZàèìòùÀÈÌÒÙáéíóúÁÉÍÓÚ0-9.,;:'"()!? ]+$/
    return re.test(title)
}

function displayError(container, message) {
    if (!container) {
        container = document.createElement('p')
        container.classList.add('error_message')
        x.parentElement.append(container)
    }
    container.textContent = message
}

function clearError(container) {
    if (container) {
        container.remove()
    }
}

function checkTitle(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (x.value == '') {
        displayError(
            errorContainer,
            "Per favore, inserisci un titolo all'opera."
        )
        return false
    } else if (!ruleComment(x.value)) {
        displayError(
            errorContainer,
            "Attenzione, il titolo dell'opera può contenere solo lettere, lettere accentate, numeri e segni di punteggiatura base (. , ; : ! ? ' \" - _ (  ) ! ? )."
        )
        return false
    } else if (x.value.length > 100) {
        displayError(
            errorContainer,
            'Attenzione, il titolo della tua opera non può superare i 100 caratteri.'
        )
        return false
    } else {
        clearError(errorContainer)
        return true
    }
}

function checkDescription(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (x.value == '') {
        displayError(
            errorContainer,
            "Per favore, inserisci una descrizione dell'opera. Per dei consigli su come comporre una buona descrizione, puoi utilizzare le indicazioni sopra riportate."
        )
        return false
    } else if (!ruleComment(x.value)) {
        displayError(
            errorContainer,
            'Attenzione, la descrizione può contenere solo lettere, lettere accentate, numeri e segni di punteggiatura base (. , ; : ! ? \' " (  ) ! ? ).'
        )
        return false
    } else if (x.value.length > 1000) {
        displayError(
            errorContainer,
            'Attenzione, la descrizione non deve superare i 1000 caratteri.'
        )
        return false
    } else {
        clearError(errorContainer)
        return true
    }
}

function checkBio(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (x.value != '') {
        if (!ruleComment(x.value)) {
            displayError(
                errorContainer,
                'Attenzione, la biografia può contenere solo lettere, lettere accentate, numeri e segni di punteggiatura base (. , ; : ! ? \' " (  ) ! ? ).'
            )
            return false
        } else if (x.value.length > 1000) {
            displayError(
                errorContainer,
                'Attenzione, la biografia non deve superare i 1000 caratteri.'
            )
            return false
        } else {
            clearError(errorContainer)
            return true
        }
    } else {
        clearError(errorContainer)
        return true
    }
}

function checkXp(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (x.value != '') {
        if (!ruleComment(x.value)) {
            displayError(
                errorContainer,
                'Attenzione, la tua esperienza può contenere solo lettere, lettere accentate, numeri e segni di punteggiatura base (. , ; : ! ? \' " (  ) ! ? ).'
            )
            return false
        } else if (x.value.length > 1000) {
            displayError(
                errorContainer,
                'Attenzione, la tua esperienza non deve superare i 1000 caratteri.'
            )
            return false
        } else {
            clearError(errorContainer)
            return true
        }
    } else {
        clearError(errorContainer)
        return true
    }
}

function checkArtshowTitle(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (x.value == '') {
        displayError(
            errorContainer,
            'Per favore, inserisci un titolo alla mostra.'
        )
        return false
    } else if (!ruleComment(x.value)) {
        displayError(
            errorContainer,
            'Attenzione, il titolo della mostra può contenere solo lettere, lettere accentate, numeri e segni di punteggiatura base (. , ; : ! ? \' " (  ) ! ? ).'
        )
        return false
    } else if (x.value.length > 100) {
        displayError(
            errorContainer,
            'Attenzione, il titolo della mostra non può superare i 100 caratteri.'
        )
        return false
    } else {
        clearError(errorContainer)
        return true
    }
}

function checkArtshowDescription(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (x.value == '') {
        displayError(
            errorContainer,
            'Per favore, inserisci una descrizione della mostra.'
        )
        return false
    } else if (!ruleComment(x.value)) {
        displayError(
            errorContainer,
            'Attenzione, la descrizione può contenere solo lettere, lettere accentate, numeri e segni di punteggiatura base (. , ; : ! ? \' " (  ) ! ? ).'
        )
        return false
    } else if (x.value.length > 1000) {
        displayError(
            errorContainer,
            'Attenzione, la descrizione non deve superare i 1000 caratteri.'
        )
        return false
    } else {
        clearError(errorContainer)
        return true
    }
}

function checkUsername(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (x.value == '') {
        displayError(
            errorContainer,
            'Per favore, inserisci lo username che desideri utilizzare.'
        )
        return false
    } else if (!ruleUsername(x.value)) {
        displayError(
            errorContainer,
            "Attenzione, lo username può contenere solo lettere minuscole e numeri. Sono ammessi punti e underscore all'interno."
        )
        return false
    } else if (x.value.length > 30) {
        displayError(
            errorContainer,
            'Attenzione, lo username non deve superare i 30 caratteri.'
        )
        return false
    } else if (x.value.length < 4) {
        displayError(
            errorContainer,
            'Attenzione, lo username deve contenere almeno 4 caratteri.'
        )
        return false
    } else {
        clearError(errorContainer)
        return true
    }
}

function checkPassword(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (x.value == '') {
        displayError(
            errorContainer,
            'Per favore, inserisci la password che desideri utilizzare.'
        )
        return false
    } else if (!rulePassword(x.value)) {
        displayError(
            errorContainer,
            'Attenzione, la password può contenere lettere non accentate e numeri. Sono ammessi i caratteri speciali !?$&@%.'
        )
        return false
    } else if (x.value.length > 30) {
        displayError(
            errorContainer,
            'Attenzione, la password non deve superare i 255 caratteri.'
        )
        return false
    } else if (x.value.length < 4) {
        displayError(
            errorContainer,
            'Attenzione, la password deve contenere almeno 4 caratteri.'
        )
        return false
    } else {
        clearError(errorContainer)
        return true
    }
}

function checkName(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (x.value == '') {
        displayError(errorContainer, 'Per favore, inserisci un valore.')
        return false
    } else if (!ruleName(x.value)) {
        displayError(
            errorContainer,
            'Per favore, assicurati di aver inserito non più di due valori, con le iniziali maiuscole e formati da almeno 2 caratteri. Non superare i 30 caratteri totali.'
        )
        return false
    } else if (x.value.length > 30) {
        displayError(
            errorContainer,
            'Attenzione, non puoi inserire più di 30 caratteri.'
        )
        return false
    } else if (x.value.length < 2) {
        displayError(
            errorContainer,
            'Attenzione, il valore deve contenere almeno 2 caratteri.'
        )
        return false
    } else {
        clearError(errorContainer)
        return true
    }
}

function checkBirthPlace(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (!ruleBirthPlace(x.value)) {
        displayError(
            errorContainer,
            'Per favore, assicurati di aver inserito il nome del luogo con le iniziali di ogni parola in maiuscolo. Sono ammesse solo lettere.'
        )
        return false
    } else if (x.value.length > 30) {
        displayError(
            errorContainer,
            'Attenzione, il nome del luogo non deve superare i 30 caratteri.'
        )
        return false
    } else {
        clearError(errorContainer)
        return true
    }
}

function checkUsernameLogin(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (x.value == '') {
        displayError(errorContainer, 'Per favore, inserisci il tuo username.')
        return false
    } else if (!ruleUsername(x.value)) {
        displayError(
            errorContainer,
            "Sicuro sia il tuo username? Ricorda che può contenere solo lettere minuscole e numeri. Sono ammessi punti e underscore all'interno."
        )
        return false
    } else if (x.value.length > 30) {
        displayError(
            errorContainer,
            'Sicuro sia il tuo username? Ricorda che non deve superare i 30 caratteri.'
        )
        return false
    } else if (x.value.length < 4) {
        displayError(
            errorContainer,
            'Sicuro sia il tuo username? Ricorda che deve contenere almeno 4 caratteri.'
        )
        return false
    } else {
        clearError(errorContainer)
        return true
    }
}

function checkPasswordLogin(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (x.value == '') {
        displayError(errorContainer, 'Per favore, inserisci la tua password.')
        return false
    } else if (!rulePassword(x.value)) {
        displayError(
            errorContainer,
            'Sicuro sia la tua password? Ricorda che può contenere lettere non accentate e numeri. Sono ammessi i caratteri speciali !?$&@%.'
        )
        return false
    } else if (x.value.length > 30) {
        displayError(
            errorContainer,
            'Sicuro sia la tua password? Ricorda che non deve superare i 255 caratteri.'
        )
        return false
    } else if (x.value.length < 4) {
        displayError(
            errorContainer,
            'Sicuro sia la tua password? Ricorda che deve contenere almeno 4 caratteri.'
        )
        return false
    } else {
        clearError(errorContainer)
        return true
    }
}

function validateLogin(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (checkUsernameLogin('username') && checkPasswordLogin('password')) {
        clearError(errorContainer)
        return true
    } else {
        displayError(
            errorContainer,
            'Per favore, compila correttamente il form seguendo gli aiuti forniti.'
        )
        return false
    }
}

function validateSignup(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (
        checkUsername('username') &&
        checkPassword('password') &&
        checkName('name') &&
        checkName('lastname') &&
        checkBirthPlace('birthplace')
    ) {
        clearError(errorContainer)
        return true
    } else {
        displayError(
            errorContainer,
            'Per favore, compila correttamente il form seguendo gli aiuti forniti.'
        )
        return false
    }
}

function validateNewArtshow(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (checkArtshowTitle('title') && checkArtshowDescription('description')) {
        clearError(errorContainer)
        return true
    } else {
        displayError(
            errorContainer,
            'Per favore, compila correttamente il form seguendo gli aiuti forniti.'
        )
        return false
    }
}

function validateNewArtwork(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (checkTitle('title') && checkDescription('description')) {
        clearError(errorContainer)
        return true
    } else {
        displayError(
            errorContainer,
            'Per favore, compila correttamente il form seguendo gli aiuti forniti.'
        )
        return false
    }
}

function validateProfile(id) {
    let x = document.getElementById(id)
    if (!x.parentElement.querySelector('.error_message')) {
        const node = document.createElement('p')
        node.classList.add('error_message')
        x.parentElement.append(node)
    }
    const errorContainer = x.parentElement.querySelector('.error_message')
    if (
        checkName('name') &&
        checkName('lastname') &&
        checkBirthPlace('birth_place') &&
        checkBio('biography') &&
        checkXp('experience')
    ) {
        clearError(errorContainer)
        return true
    } else {
        displayError(
            errorContainer,
            'Per favore, compila correttamente il form seguendo gli aiuti forniti.'
        )
        return false
    }
}
