<?php include __DIR__ . '/../include/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="text-center mb-4">Edit Event</h2>
            <?php if (!empty($_SESSION['errors'])) : ?>
                <div class="alert alert-danger p-2 pb-0">
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error) : ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <form
                action="<?= BASE_URL ?>events/<?php echo htmlspecialchars($event['id']); ?>"
                method="POST"
                class="shadow p-4 rounded bg-light"
            >
                <input type="hidden" name="_method" value="PUT">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="eventName" class="form-label">Event Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="eventName"
                                name="name"
                                placeholder="Enter event name"
                                value="<?php echo $event['name']; ?>"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label for="eventLocation" class="form-label">Event Location</label>
                            <input
                                type="text"
                                class="form-control"
                                id="eventLocation"
                                name="location"
                                placeholder="Enter event location"
                                value="<?php echo $event['location']; ?>"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label for="totalSeat" class="form-label">Total Seat</label>
                            <input
                                type="number"
                                class="form-control"
                                id="totalSeat"
                                name="total_seat"
                                placeholder="Enter event total seat"
                                value="<?php echo $event['total_seat']; ?>"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label for="eventDate" class="form-label">Event Date</label>
                            <input
                                type="datetime-local"
                                class="form-control"
                                id="eventDate"
                                name="date"
                                value="<?php echo $event['date']; ?>"
                            >
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Event Description</label>
                            <textarea
                                class="form-control"
                                id="eventDescription"
                                name="description"
                                rows="12"
                                placeholder="Enter event description"
                            ><?php echo $event['description']; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-1">
                    <a href="<?= BASE_URL ?>events" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary me-2">Update Event</button>
                </div>

            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../include/footer.php'; ?>


