const maxTasksPerDay = 5;
let points = 0;
let tasksCompletedToday = 0;

function completeTask(button, url) {
    window.open(url, '_blank');

    const listItem = button.parentElement;

    if (!listItem.classList.contains('completed')) {
        listItem.classList.add('completed');
        button.disabled = true;
        points += 1;
        tasksCompletedToday += 1;
        document.getElementById('points').textContent = points;
        saveData();

        if (tasksCompletedToday >= maxTasksPerDay) {
            lockTasks();
        }
    }
}

function lockTasks() {
    document.querySelectorAll('.task-list li button').forEach(button => {
        button.disabled = true;
    });

    document.getElementById('cooldownMessage').style.display = 'block';
    localStorage.setItem('lastCompletionDate', new Date().toISOString());
}

function resetTasks() {
    document.querySelectorAll('.task-list li').forEach(listItem => {
        listItem.classList.remove('completed');
        listItem.querySelector('button').disabled = false;
    });

    tasksCompletedToday = 0;
    document.getElementById('cooldownMessage').style.display = 'none';
    document.getElementById('points').textContent = points;
    saveData();
}

function saveData() {
    const data = {
        points: points,
        tasksCompletedToday: tasksCompletedToday
    };
    localStorage.setItem('taskData', JSON.stringify(data));
}

function loadData() {
    const savedData = JSON.parse(localStorage.getItem('taskData'));
    if (savedData) {
        points = savedData.points;
        tasksCompletedToday = savedData.tasksCompletedToday;
        document.getElementById('points').textContent = points;

        if (tasksCompletedToday >= maxTasksPerDay) {
            lockTasks();
        }
    }

    const lastCompletionDate = localStorage.getItem('lastCompletionDate');
    if (lastCompletionDate) {
        const lastCompletion = new Date(lastCompletionDate);
        const now = new Date();

        if (now.getDate() !== lastCompletion.getDate() || now - lastCompletion >= 24 * 60 * 60 * 1000) {
            resetTasks();
        }
    }
}

window.onload = function() {
    loadData();
}