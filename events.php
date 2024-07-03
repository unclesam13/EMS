<?php
require 'classes/Event.php';
require 'classes/Session.php';

Session::start();
$user_id = Session::get('user_id');

if (!$user_id) {
    header('Location: login.php');
    exit;
}

$event = new Event();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $event->create($user_id, $_POST['event_name'], $_POST['description'], $_POST['date_time'], $_POST['location']);
    } elseif (isset($_POST['update'])) {
        $event->update($_POST['event_id'], $_POST['event_name'], $_POST['description'], $_POST['date_time'], $_POST['location']);
    } elseif (isset($_POST['delete'])) {
        $event->delete($_POST['event_id']);
    }
}

$events = $event->read($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Events</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- Optional custom styles -->
</head>
<body>
    <div class="container">
        <div class="mt-5">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <div class="mt-5">
            <h2>Create Event</h2>
            <form method="POST" action="events.php" class="mb-4">
                <div class="form-group">
                    <label for="event_name">Event Name</label>
                    <input type="text" name="event_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="date_time">Date & Time</label>
                    <input type="datetime-local" name="date_time" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" name="location" class="form-control" required>
                </div>
                <button type="submit" name="create" class="btn btn-primary">Create Event</button>
            </form>
        </div>

        <div class="mt-5">
            <h2>Your Events</h2>
            <?php foreach ($events as $event): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="POST" action="events.php">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <div class="form-group">
                                <label for="event_name">Event Name</label>
                                <input type="text" name="event_name" class="form-control" value="<?php echo $event['event_name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control" rows="3" required><?php echo $event['description']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="date_time">Date & Time</label>
                                <input type="datetime-local" name="date_time" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($event['date_time'])); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" name="location" class="form-control" value="<?php echo $event['location']; ?>" required>
                            </div>
                            <button type="submit" name="update" class="btn btn-primary mr-2">Update Event</button>
                            <button type="submit" name="delete" class="btn btn-danger">Delete Event</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
