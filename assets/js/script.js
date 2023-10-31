const instagramIcon = document.getElementById("instagram");

instagramIcon.addEventListener("mouseenter", function () {
    instagramIcon.classList.add("fa-beat");
});

instagramIcon.addEventListener("mouseleave", function () {
    instagramIcon.classList.remove("fa-beat");
});

const githubIcon = document.getElementById("github");

githubIcon.addEventListener("mouseenter", function () {
    githubIcon.classList.add("fa-beat");
});

githubIcon.addEventListener("mouseleave", function () {
    githubIcon.classList.remove("fa-beat");
});

const twitterIcon = document.getElementById("twitter");

twitterIcon.addEventListener("mouseenter", function () {
    twitterIcon.classList.add("fa-beat");
});

twitterIcon.addEventListener("mouseleave", function () {
    twitterIcon.classList.remove("fa-beat");
});

// Haal de mappenstructuur op met behulp van Fetch API
const folderStructure = document.getElementById('folderStructure');

fetch('/public/index.php?controller=School&method=files')
    .then(response => response.json())
    .then(data => {
        folderStructure.innerHTML = createFolderStructure(data, '');
        attachClickListeners();
    })
    .catch(error => {
        console.error('Er is een fout opgetreden bij het ophalen van de mappenstructuur:', error);
    });

// Functie om de mappenstructuur op te bouwen
function createFolderStructure(data, folderPath) {
    let html = '';

    // Terug naar bovenliggende map knop
    if (folderPath) {
        const parentFolder = folderPath.substring(0, folderPath.lastIndexOf('/'));
        html += `
        <div class="list-item folder text-hover" role="button" data-path="${parentFolder}"
        data-id="13" data-item-sortable-id="0" 
        draggable="true" role="option" aria-grabbed="false">
            <div>
                <span class="w-40 avatar">
                <i class="fa-sharp fa-solid fa-arrow-left-long"></i></span>
            </div>
            <div class="flex">
                <span class="item-author" 
                data-path="${parentFolder}" role="button" data-abc="true">..${folderPath}</span>
            </div>
            <div class="no-wrap">
                <div class="item-date text-muted text-sm d-none d-md-block"></div>
            </div>
        </div>
    `;
    }

    // Loop door de mappen
    data.folders.forEach(folder => {
        const folderLink = `${folderPath}/${folder}`;
        const lastModified = ''; // Voeg hier de informatie over de laatste wijziging toe

        html += `
            <div class="list-item folder text-hover" role="button" data-path="${folderLink}" data-item-sortable-id="0" 
            draggable="true" role="option" aria-grabbed="false">
                <div>
                    <span class="w-40 avatar"><i class="fa-solid fa-folder"></i></span>
                </div>
                <div class="flex">
                    <span class="item-author" data-abc="true">${folder}</span>
                </div>
                <div class="no-wrap">
                    <div class="item-date text-muted text-sm d-none d-md-block">${lastModified}</div>
                </div>
            </div>
        `;
    });

    // Loop door de bestanden
    data.files.forEach(file => {
        const fileLink = `${folderPath}/${file}`;
        const fileSize = '4.4mb'; // Voeg hier de bestandsgrootte van het bestand toe
        const lastModified = '12-02-2023 14:47'; // Voeg hier de informatie over de laatste wijziging toe

        html += `
            <a href="http://localhost/school${fileLink}" class="list-item folder text-hover text-color no-a-styling"
             role="button" data-path="${fileLink}" data-id="13" data-item-sortable-id="0" 
            draggable="true" role="option" aria-grabbed="false">
                <div>
                    <span class="w-40 avatar"><i class="fa-regular fa-file"></i></span>
                </div>
                <div class="flex">
                    <div class="no-a-styling" data-abc="true">${file}</div>
                    <div class="item-except text-muted text-sm h-1x">${fileSize}</div>
                </div>
                <div class="no-wrap">
                    <div class="item-date text-muted text-sm d-none d-md-block">${lastModified}</div>
                </div>
            </a>
        `;
    });

    return html;
}


// Functie om klikgebeurtenissen aan mappen en bestanden te koppelen
function attachClickListeners() {
    const folders = document.getElementsByClassName('folder');
    const files = document.getElementsByClassName('file');

    Array.from(folders).forEach(folder => {
        folder.addEventListener('click', () => {
            const folderPath = folder.getAttribute('data-path');
            fetchFolderContent(folderPath);
        });
    });

    Array.from(files).forEach(file => {
        file.addEventListener('click', (event) => {
            event.preventDefault();
            const fileName = file.textContent;
            openFile(fileName);
        });
    });
}

// Functie om de inhoud van een map op te halen
function fetchFolderContent(folderPath) {
    fetch(`/public/index.php?controller=School&method=files&folder=${encodeURIComponent(folderPath)}`)
        .then(response => response.json())
        .then(data => {
            const folderContent = createFolderStructure(data, folderPath);
            folderStructure.innerHTML = folderContent;
            attachClickListeners();
        })
        .catch(error => {
            console.error('Er is een fout opgetreden bij het ophalen van de mapinhoud:', error);
        });
}

// Functie om een bestand te openen (bijv. door naar een nieuwe pagina te navigeren)
function openFile(fileName) {
    // Implementeer hier de gewenste actie bij het openen van een bestand
    console.log('Bestand geopend:', fileName);
    window.open(fileName, '_blank');
}

// Functie om het huidige pad van de huidige map op te halen
function getCurrentFolder() {
    const currentFolderElement = document.querySelector('.folder.active');
    if (currentFolderElement) {
        return currentFolderElement.getAttribute('data-path');
    }
    return '';
}

const circle = document.getElementById('circle');

document.addEventListener('mousemove', ({ clientX, clientY }) => {
    const scrollX = window.scrollX;
    const scrollY = window.scrollY;

    const circleSize = 50; // Size of the fading circle in pixels
    const maxOpacity = 0.5; // Maximum opacity of the circle (0 to 1)
    const fadeRadius = 20; // Radius around the mouse cursor where the circle starts fading

    const circleX = clientX - circleSize / 2 + scrollX;
    const circleY = clientY - circleSize / 2 + scrollY;
    const distance = Math.sqrt((circleX - (clientX + scrollX)) ** 2 + (circleY - (clientY + scrollY)) ** 2);
    const opacity = 1 - Math.max(0, (distance - circleSize + fadeRadius)) / fadeRadius;

    circle.style.left = `${circleX}px`;
    circle.style.top = `${circleY}px`;
    circle.style.opacity = maxOpacity * opacity;

    // Prevent scrolling near the edges
    const edgeThreshold = 50; // Adjust this threshold as needed
    if (clientX < edgeThreshold || clientX > window.innerWidth - edgeThreshold ||
        clientY < edgeThreshold || clientY > window.innerHeight - edgeThreshold) {
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
    } else {
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
    }
});

// Restore scrolling when the mouse leaves the document
document.addEventListener('mouseleave', () => {
    document.documentElement.style.overflow = '';
    document.body.style.overflow = '';
});

