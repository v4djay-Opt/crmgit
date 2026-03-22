<?php 
include 'db.php'; 

// Auto-migration: Create columns if they don't exist
$migrations = [
    'rm' => "ALTER TABLE leads ADD COLUMN rm VARCHAR(255) AFTER message",
    'project' => "ALTER TABLE leads ADD COLUMN project VARCHAR(255) AFTER rm"
];

foreach ($migrations as $column => $sql) {
    $check = mysqli_query($conn, "SHOW COLUMNS FROM leads LIKE '$column'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, $sql);
    }
}

$msg = "";
if(isset($_POST['submit'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $rm = mysqli_real_escape_string($conn, $_POST['rm']);
    $project = mysqli_real_escape_string($conn, $_POST['project']);

    $query = "INSERT INTO leads (name, email, phone, message, rm, project) 
              VALUES ('$name', '$email', '$phone', '$message', '$rm', '$project')";

    if(mysqli_query($conn, $query)){
        $msg = "Lead Captured Successfully! ✨";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM | Lead Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg);
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 3rem;
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: 600;
            background: linear-gradient(to right, #818cf8, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        header p {
            color: var(--text-muted);
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 768px) {
            .grid { grid-template-columns: 350px 1fr; }
        }

        /* Card Styles */
        .card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        input, textarea {
            width: 100%;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 0.8rem 1rem;
            color: white;
            transition: all 0.3s ease;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
        }

        button {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 0.75rem;
            padding: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        button:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .success-msg {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            text-align: center;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        /* Lead List Styles */
        .lead-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .lead-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            padding: 1.5rem;
            border-radius: 1rem;
            transition: transform 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .lead-item:hover {
            transform: scale(1.02);
            background: rgba(255, 255, 255, 0.05);
        }

        .lead-info h3 {
            font-size: 1.2rem;
            margin-bottom: 0.2rem;
        }

        .lead-info .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            background: rgba(99, 102, 241, 0.2);
            color: #818cf8;
            border-radius: 2rem;
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .lead-meta {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        .lead-rm {
            text-align: right;
            font-size: 0.85rem;
        }

        .lead-rm span {
            display: block;
            color: var(--text-muted);
        }

        .lead-rm b {
            color: #f472b6;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>LeadFlow CRM</h1>
        <p>Manage your leads with style and efficiency.</p>
    </header>

    <div class="grid">
        <!-- Form Section -->
        <div class="card">
            <?php if($msg): ?>
                <div class="success-msg"><?php echo $msg; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="Ex: Rahul Sharma" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="rahul@example.com" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="+91 98765 43210" required>
                </div>
                <div class="form-group">
                    <label>Project Name</label>
                    <input type="text" name="project" placeholder="Property-X" required>
                </div>
                <div class="form-group">
                    <label>Assigned RM</label>
                    <input type="text" name="rm" placeholder="Dhananjay Singh" required>
                </div>
                <div class="form-group">
                    <label>Message/Notes</label>
                    <textarea name="message" rows="3" placeholder="Interested in 2BHK..." required></textarea>
                </div>
                <button type="submit" name="submit">Capture Lead</button>
            </form>
        </div>

        <!-- List Section -->
        <div class="card">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem;">Recent Leads</h2>
            <div class="lead-list">
                <?php
                $result = mysqli_query($conn, "SELECT * FROM leads ORDER BY id DESC");
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)){
                        ?>
                        <div class="lead-item">
                            <div class="lead-info">
                                <span class="badge"><?php echo htmlspecialchars($row['project']); ?></span>
                                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                <div class="lead-meta">
                                    📧 <?php echo htmlspecialchars($row['email']); ?> | 
                                    📞 <?php echo htmlspecialchars($row['phone']); ?>
                                </div>
                                <div style="margin-top: 0.5rem; color: #cbd5e1; font-size: 0.9rem;">
                                    "<?php echo htmlspecialchars($row['message']); ?>"
                                </div>
                            </div>
                            <div class="lead-rm">
                                <span>Managed by</span>
                                <b><?php echo htmlspecialchars($row['rm']); ?></b>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p style='text-align:center; color:var(--text-muted); padding: 2rem;'>No leads found. Start by adding one!</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
