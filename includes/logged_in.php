<body id="main-layout" data-logged-in="1">
    <nav class="bar">
        <ul>
            <li id="home"><h1>jStitch</h1></li>
            <li>Hello there, <b><?php echo $_SESSION["username"] ?></b>!</li>
            <li><a href="#" id="log-out">Log Out</a></li>
        </ul>
    </nav>
    <div id="main-area">
        <p>You've crocheted</p>
        <span id="current-stitch-count" class="count">0</span>
        <p>stitches!</p>
    </div>
    <footer class="bar">
        <ul>
            <li><a href="#" id="clear-current">Clear Current Stitch Count</a></li>
            <li>Total Stitch Count: <span id="total-stitch-count" class="count">0</span></li>
        </ul>
    </footer>
</body>
