const folderStructure = document.getElementById('folderStructure');

function createFolderStructure(data, folderPath) {
    let html = '';

    if (folderPath) {
        const parentFolder = folderPath.substring(0, folderPath.lastIndexOf('/'));
        html += `
            <button class="folder-item js-folder" type="button" data-path="${parentFolder}">
                <i class="fa-solid fa-arrow-left-long text-cyan-300"></i>
                <span class="truncate">Terug naar ..${folderPath}</span>
            </button>
        `;
    }

    data.folders.forEach(folder => {
        const folderLink = `${folderPath}/${folder}`;
        html += `
            <button class="folder-item js-folder" type="button" data-path="${folderLink}">
                <i class="fa-solid fa-folder text-cyan-300"></i>
                <span class="truncate">${folder}</span>
            </button>
        `;
    });

    data.files.forEach(file => {
        html += `
            <div class="folder-item">
                <i class="fa-regular fa-file text-slate-300"></i>
                <span class="truncate">${file}</span>
            </div>
        `;
    });

    return html;
}

function attachFolderListeners() {
    const folders = document.querySelectorAll('.js-folder');
    folders.forEach(folder => {
        folder.addEventListener('click', () => {
            const folderPath = folder.getAttribute('data-path') || '';
            fetchFolderContent(folderPath);
        });
    });
}

function fetchFolderContent(folderPath = '') {
    fetch(`/index.php?controller=School&method=files&folder=${encodeURIComponent(folderPath)}`)
        .then(response => response.json())
        .then(data => {
            folderStructure.innerHTML = createFolderStructure(data, folderPath);
            attachFolderListeners();
        })
        .catch(error => {
            console.error('Fout bij ophalen van mapinhoud:', error);
        });
}

if (folderStructure) {
    fetchFolderContent('');
}

const circle = document.getElementById('circle');
if (circle) {
    document.addEventListener('mousemove', ({ clientX, clientY }) => {
        const scrollX = window.scrollX;
        const scrollY = window.scrollY;
        const circleSize = 55;
        const maxOpacity = 0.55;
        const fadeRadius = 20;

        const circleX = clientX - circleSize / 2 + scrollX;
        const circleY = clientY - circleSize / 2 + scrollY;
        const distance = Math.sqrt((circleX - (clientX + scrollX)) ** 2 + (circleY - (clientY + scrollY)) ** 2);
        const opacity = 1 - Math.max(0, (distance - circleSize + fadeRadius)) / fadeRadius;

        circle.style.left = `${circleX}px`;
        circle.style.top = `${circleY}px`;
        circle.style.opacity = `${maxOpacity * opacity}`;
    });
}

