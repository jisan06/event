<?php include __DIR__ . '/../include/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Event List</h2>
                <a href="/events/create" class="btn btn-primary">
                    Create Event
                </a>
            </div>
            <div class="table-responsive shadow p-4 rounded bg-light">
                <?php if (!empty($_SESSION['success'])) : ?>
                    <div class="alert alert-success p-2">
                        <?php echo htmlspecialchars($_SESSION['success']); ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                <?php include __DIR__ . '/../include/filter.php' ?>
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>
                            Name
                            <a href="?order_by=name&order=ASC<?= isset($_GET['page']) ? '&page=' . $_GET['page'] : ''; ?>"
                               class="btn btn-link btn-sm p-0 text-decoration-none">▲</a>
                            <a href="?order_by=name&order=DESC<?= isset($_GET['page']) ? '&page=' . $_GET['page'] : ''; ?>"
                               class="btn btn-link btn-sm p-0 text-decoration-none">▼</a>
                        </th>
                        <th>
                            Location
                            <a href="?order_by=location&order=ASC<?= isset($_GET['page']) ? '&page=' . $_GET['page'] : ''; ?>"
                               class="btn btn-link btn-sm p-0 text-decoration-none">▲</a>
                            <a href="?order_by=location&order=DESC<?= isset($_GET['page']) ? '&page=' . $_GET['page'] : ''; ?>"
                               class="btn btn-link btn-sm p-0 text-decoration-none">▼</a>
                        </th>
                        <th>
                            Date
                            <a href="?order_by=date&order=ASC<?= isset($_GET['page']) ? '&page=' . $_GET['page'] : ''; ?>"
                               class="btn btn-link btn-sm p-0 text-decoration-none">▲</a>
                            <a href="?order_by=date&order=DESC<?= isset($_GET['page']) ? '&page=' . $_GET['page'] : ''; ?>"
                               class="btn btn-link btn-sm p-0 text-decoration-none">▼</a>
                        </th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($events)) : ?>
                        <?php foreach ($events as $key => $event) : ?>
                            <tr>
                                <th scope="row"><?php echo $key + 1; ?></th>
                                <td><?php echo htmlspecialchars($event['name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($event['location'] ?? ''); ?></td>
                                <td>
                                    <?php echo !empty($event['date']) ? date('d-m-Y h:i a', strtotime($event['date'])) : 'N/A'; ?>
                                </td>
                                <td class="text-center">
                                    <a href="/events/<?php echo $event['id']; ?>"
                                       class="btn btn-warning btn-sm px-2 py-2"
                                    >
                                        Edit
                                    </a>
                                    <form
                                        action="/events/<?php echo $event['id']; ?>"
                                        method="POST"
                                        style="display:inline;"
                                    >
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button
                                            type="submit"
                                            class="btn btn-danger px-2 py-2"
                                            onclick="return confirm('Are you sure you want to delete this event?');"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center">No events found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <!-- Previous Button -->
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?order_by=<?= $order_by; ?>&order=<?= $order; ?>&page=<?= max(1, $page - 1); ?>">Previous</a>
                            </li>

                            <!-- Page Numbers -->
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?order_by=<?= $order_by; ?>&order=<?= $order; ?>&page=<?= $i; ?>"><?= $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next Button -->
                            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                   href="?order_by=<?= $order_by; ?>&order=<?= $order; ?>&page=<?= min($total_pages, $page + 1); ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

