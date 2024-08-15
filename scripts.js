document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('a');
    links.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            setTimeout(() => {
                window.location.href = link.href;
            }, 500);  // Simulates loading animation
        });
    });

    const completeTaskButtons = document.querySelectorAll('.complete-task');
    completeTaskButtons.forEach(button => {
        button.addEventListener('click', () => {
            alert('Task completed! You earned 50 points.');
            // Increment user's points in backend
        });
    });

    const referButton = document.querySelector('.refer button');
    if (referButton) {
        referButton.addEventListener('click', () => {
            alert('Referral link generated! Share it with friends.');
            // Generate referral link in backend
        });
    });
});
