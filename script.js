let userSpentMoreThan30Seconds = false;

setTimeout(() => {
    userSpentMoreThan30Seconds = true;
    console.log(userSpentMoreThan30Seconds);
}, 30000);


document.getElementById('leadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('userSpentMoreThan30Seconds', userSpentMoreThan30Seconds ? 1 : 0);

    fetch('amo.php', {
        method: 'POST',
        body: formData
    });
});

