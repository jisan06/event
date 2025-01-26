<form method="GET" action="/events" class="mb-4">
    <div class="row">
        <!-- Name Filter -->
        <div class="col-md-3">
            <label for="name" class="form-label">Event Name</label>
            <input type="text" name="name" id="name" class="form-control"
                   value="<?= htmlspecialchars($_GET['name'] ?? '') ?>"
                   placeholder="Enter event name">
        </div>

        <!-- Location Filter -->
        <div class="col-md-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control"
                   value="<?= htmlspecialchars($_GET['location'] ?? '') ?>"
                   placeholder="Enter location">
        </div>

        <!-- Date Range Filter -->
        <div class="col-md-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control"
                   value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control"
                   value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">
        </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-3 text-end">
        <a href="/events" class="btn btn-secondary">Reset</a>
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>