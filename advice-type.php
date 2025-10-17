<?php require_once('header.php'); ?>

<style>
body {
    background-image: none;
    background-color: initial;
    margin: 0;
    padding: 0;
    display: block;
}

.main-content-area {
    background-image: url('https://images.unsplash.com/photo-1517400538804-0c5a27c7324d?q=80&w=1974&auto=format&fit=crop');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-color: #4f806b;
    background-blend-mode: overlay;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    max-width: 900px;
    width: 90%;
    margin: 40px auto;
    text-align: center;
}

.main-content-area h2 {
    color: white;
    font-size: 2.2em;
    margin-bottom: 35px;
}

.card-container {
    display: flex;
    justify-content: center;
    align-items: stretch;
    flex-wrap: wrap;
    gap: 30px;
}

.card {
    background-color: white;
    border: 3px solid #4f806b; /* green border */
    border-radius: 10px;
    overflow: hidden;
    width: 320px;
    box-shadow: 0 5px 10px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card img {
    width: 100%;
    height: 420px;
    object-fit: cover;
}

.card button {
    background-color: rgb(113, 187, 129); /* same as your submit button */
    color: white;
    border: none;
    width: 100%;
    padding: 12px 0;
    font-size: 1.1em;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.card button:hover {
    background-color: rgb(89, 134, 96);
}

@media (max-width: 700px) {
    .card-container {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<div class="main-content-area">
    <h2>Advice Type</h2>

    <div class="card-container">
        <!-- AI Card -->
        <div class="card">
            <img src="assets/uploads/ai.png" alt="AI Advice">
            <form action="ai_advice.php" method="get">
                <button type="submit">AI</button>
            </form>
        </div>

        <!-- Expert Card -->
        <div class="card">
            <img src="assets/uploads/expert.png" alt="Expert Advice">
            <form action="upload_problem.php" method="get">
                <button type="submit">Expert</button>
            </form>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
