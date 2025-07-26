const maxTop = window.innerHeight / 1.5;
const screenWidth = window.innerWidth;

let cloudCount;
if (screenWidth < 480) {
    cloudCount = 3; // mobiel
} else if (screenWidth < 1024) {
    cloudCount = 6; // tablet
} else {
    cloudCount = 12; // desktop
}

for (let i = 0; i < cloudCount; i++) {
    const cloud = document.createElement('div');
    cloud.classList.add('cloud');

    const width = Math.random() * 80 + 50;
    const height = width * 0.6;
    cloud.style.width = width + 'px';
    cloud.style.height = height + 'px';

    const top = Math.random() * maxTop;
    cloud.style.top = top + 'px';

    // Willekeurige horizontale startpositie
    const left = Math.random() * screenWidth;
    cloud.style.left = left + 'px';

    // Willekeurige snelheid
    const duration = Math.random() * 60 + 40;
    cloud.style.animation = `moveCloud ${duration}s linear infinite`;

    // Pseudo-elementen voor wolkvorm
    cloud.style.setProperty('--before-width', width * 0.6 + 'px');
    cloud.style.setProperty('--before-height', width * 0.6 + 'px');
    cloud.style.setProperty('--before-left', width * 0.1 + 'px');
    cloud.style.setProperty('--before-top', -width * 0.2 + 'px');

    cloud.style.setProperty('--after-width', width * 0.7 + 'px');
    cloud.style.setProperty('--after-height', width * 0.7 + 'px');
    cloud.style.setProperty('--after-left', width * 0.3 + 'px');
    cloud.style.setProperty('--after-top', -width * 0.25 + 'px');

    document.body.appendChild(cloud);
}

