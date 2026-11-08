<?php
// Active server session
session_start();

// Connect to the database using your existing db.php script
require_once 'db.php';

// Query 1: Join users and player_stats, sorted by wins then win rate
$sql = "SELECT users.username, 
               player_stats.wins, 
               player_stats.losses, 
               player_stats.win_rate 
        FROM users 
        JOIN player_stats ON users.id = player_stats.user_id 
        ORDER BY player_stats.wins DESC, player_stats.win_rate DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Tic Tac Toe - Leaderboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
  </head>
<body>

  <!-- Navigation Bar -->
  <nav>
    <a href="index.html">Home</a>
    <a href="play.html">Play Now</a>
    <a href="info.html">Info</a>
    <a href="leaderboard.php" class="active">Leaderboard</a>
    <?php if (isset($_SESSION['username'])): ?>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.html">Login</a>
    <?php endif; ?>
  </nav>

  <!-- Page Header -->
  <header>
    <h1>CAMPUS LEADERBOARD</h1>
    <p>Top records, win percentages, and active streaks</p>
  </header>

  <!-- Main Stats Content -->
  <main>
    <section>
      <h2>Leaderboard</h2>
      <p>Rankings update automatically following verified match completions. Minimum 10 matches required to qualify for Top 5.</p>

      <!-- Semantic Table for Win/Loss tracking -->
      <table style="width: 100%; max-width: 700px; margin: 30px auto; border-collapse: collapse;">
        <thead>
          <tr style="background-color: #5F259F; color: #FFFFFF;">
            <th style="padding: 12px; border: 1px solid #ddd;">Rank</th>
            <th style="padding: 12px; border: 1px solid #ddd;">Player</th>
            <th style="padding: 12px; border: 1px solid #ddd;">Wins</th>
            <th style="padding: 12px; border: 1px solid #ddd;">Losses</th>
            <th style="padding: 12px; border: 1px solid #ddd;">Draws</th>
            <th style="padding: 12px; border: 1px solid #ddd;">Win Rate</th>
          </tr>
        </thead>
        <tbody style="text-align: center;">
          <?php if ($result && $result->num_rows > 0): ?>
            <?php 
              $rank = 1; 
              while ($row = $result->fetch_assoc()): 
            ?>
              <tr style="background-color: <?php echo ($rank % 2 === 1) ? '#f9f9f9' : '#ffffff'; ?>;">
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo $rank++; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($row['username']); ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo (int)$row['wins']; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo (int)$row['losses']; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo (int)$row['draws']; ?></td>
                <td style="padding: 10px; border: 1px solid #ddd;"><?php echo number_format($row['win_rate'], 1); ?>%</td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" style="padding: 16px; border: 1px solid #ddd;">No player records found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>
  </main>

  <!-- Shared Footer -->
  <footer> 
    <p>Tic Tac Toe Web Page Project</p>
  </footer>

</body>
</html>
<?php
$conn->close();
?>