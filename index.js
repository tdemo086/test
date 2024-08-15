let users = JSON.parse(localStorage.getItem('users')) || [];
let currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;

function saveUser(user) {
    const userIndex = users.findIndex(u => u.username === user.username);
    if (userIndex !== -1) {
        users[userIndex] = user;
        localStorage.setItem('users', JSON.stringify(users));
    }
}

function showSection(section) {
    document.querySelectorAll('section').forEach(sec => sec.classList.add('hidden'));
    document.getElementById(section).classList.remove('hidden');
    gsap.fromTo(`#${section}`, { opacity: 0 }, { opacity: 1, duration: 1 });

    if (section === 'profile') {
        updateProfile();
    } else if (section === 'tasks') {
        updateTasks();
    }
}

function updateProfile() {
    if (currentUser) {
        document.getElementById('profile-username').textContent = currentUser.username;
        document.getElementById('profile-points').textContent = currentUser.points;
        document.getElementById('profile-photo').src = currentUser.photo;
        document.getElementById('ref-link').textContent = `${window.location.href}?ref=${currentUser.username}`;
    }
}
function completeTask(taskNumber) {
if (currentUser) {
const now = Date.now();
const oneDay = 24 * 60 * 60 * 1000;

if (now - currentUser.lastTaskCompletion < oneDay) {
    alert('You can only complete tasks once every 24 hours.');
    return;
}

currentUser.points += 50;
currentUser.lastTaskCompletion = now;
saveUser(currentUser);
alert(`Task ${taskNumber} completed! You've earned 50 points.`);
showSection('tasks');
}
}

document.getElementById('login-form').addEventListener('submit', (e) => {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const user = users.find(u => u.username === username && u.password === password);

    if (user) {
        currentUser = user;
        localStorage.setItem('currentUser', JSON.stringify(currentUser));
        document.getElementById('welcome-username').textContent = currentUser.username;
        showSection('home');
    } else {
        alert('Invalid username or password');
    }
});

document.getElementById('register-form').addEventListener('submit', (e) => {
    e.preventDefault();
    const username = document.getElementById('reg-username').value;
    const password = document.getElementById('reg-password').value;
    const photo = document.getElementById('reg-photo').files[0];
    const referrerUsername = document.getElementById('referrer').value.trim();

    if (users.find(u => u.username === username)) {
        alert('Username already exists');
        return;
    }

    const reader = new FileReader();
    reader.onload = function () {
        const newUser = {
            username,
            password,
            photo: reader.result,
            points: 0,
            spinsLeft: 3,
            lastTaskCompletion: 0,
            tasksCompleted: 0
        };
        users.push(newUser);
        localStorage.setItem('users', JSON.stringify(users));
        localStorage.setItem('currentUser', JSON.stringify(newUser));
        currentUser = newUser;
        document.getElementById('welcome-username').textContent = currentUser.username;

        if (referrerUsername) {
            const referrer = users.find(u => u.username === referrerUsername);
            if (referrer) {
                referrer.points += 100;
                saveUser(referrer);
            }
        }

        showSection('home');
    };
    reader.readAsDataURL(photo);
});

function completeTask(taskNumber) {
    if (currentUser) {
        const now = Date.now();
        const oneDay = 24 * 60 * 60 * 1000;

        if (now - currentUser.lastTaskCompletion < oneDay) {
            alert('You can only complete tasks once every 24 hours.');
            return;
        }

        currentUser.points += 50;
        currentUser.tasksCompleted = Math.max(currentUser.tasksCompleted, taskNumber);
        currentUser.lastTaskCompletion = now;
        saveUser(currentUser);
        alert(`Task ${taskNumber} completed! You've earned 50 points.`);
        showSection('tasks');
    }
}
document.getElementById('spin-btn').addEventListener('click', function () {
if (currentUser && currentUser.spinsLeft > 0) {
currentUser.spinsLeft -= 1;
let spinAngle = Math.floor(Math.random() * 360) + 1080; // random spin between 1080 and 1440 degrees
let wheel = document.getElementById('wheel');
let spinBtn = document.getElementById('spin-btn');
wheel.style.transition = 'transform 4s ease-out';
wheel.style.transform = `rotate(${spinAngle}deg)`;

spinBtn.disabled = true;
setTimeout(() => {
    wheel.style.transition = '';
    wheel.style.transform = `rotate(${spinAngle % 360}deg)`;

    let pointsEarned = 0;
    let sector = (spinAngle % 360) / 90; // Assuming 4 sectors
    if (sector >= 0 && sector < 1) {
        pointsEarned = 10;
    } else if (sector >= 1 && sector < 2) {
        pointsEarned = 25;
    } else if (sector >= 2 && sector < 3) {
        pointsEarned = 50;
    } else {
        pointsEarned = 100;
    }

    currentUser.points += pointsEarned;
    saveUser(currentUser);
    alert(`You earned ${pointsEarned} points!`);
    spinBtn.disabled = false;
    showSection('tasks');
}, 4000); // Match the duration of the spin transition
} else {
alert('No spins left for today!');
}
});

function logout() {
    localStorage.removeItem('currentUser');
    currentUser = null;
    showSection('login');
}

const urlParams = new URLSearchParams(window.location.search);
const referrer = urlParams.get('ref');

if (referrer) {
    document.getElementById('referrer').value = referrer;
}

if (currentUser) {
    document.getElementById('welcome-username').textContent = currentUser.username;
    showSection('home');
} else {
    showSection('login');
}
