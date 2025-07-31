<div class="content-wrapper">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-0">Support Tickets</h1>
                <p class="text-muted mb-0">Get help and track your support requests</p>
            </div>
            <a href="#" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#newTicketModal">
                <i class="ri-add-line me-1"></i>
                <span>Create New Ticket</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Total Tickets</h6>
                        <span class="bg-primary bg-opacity-10 rounded-circle p-2"><i class="ri-customer-service-2-line text-primary"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">24</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Open Tickets</h6>
                        <span class="bg-warning bg-opacity-10 rounded-circle p-2"><i class="ri-time-line text-warning"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">8</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">In Progress</h6>
                        <span class="bg-info bg-opacity-10 rounded-circle p-2"><i class="ri-loader-4-line text-info"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">5</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Resolved</h6>
                        <span class="bg-success bg-opacity-10 rounded-circle p-2"><i class="ri-check-double-line text-success"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">11</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtering and Status Tabs -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row mb-4">
                <!-- Status Tabs -->
                <div class="col-md-8 mb-2 mb-md-0">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-primary">
                            All Tickets (24)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            Open (8)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            In Progress (5)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                            Resolved (11)
                        </button>
                    </div>
                </div>
                <!-- Search and Filter -->
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" placeholder="Search tickets...">
                        <span class="input-group-text bg-white border-0"><i class="ri-search-line text-muted"></i></span>
                    </div>
                </div>
            </div>

            <!-- Mobile Cards View -->
            <div class="d-md-none">
                <div class="row g-3">
                    <!-- Open Ticket -->
                    <div class="col-12">
                        <div class="card shadow-sm border-start border-4 border-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="mb-1">Payment Issue - Transaction Failed</h5>
                                        <div class="text-muted small">Ticket #TKT-2024-001</div>
                                    </div>
                                    <div>
                                        <span class="badge bg-warning">Open</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 small"><span class="text-muted">Priority:</span> <span class="fw-semibold text-danger">High</span></div>
                                    <div class="col-6 small"><span class="text-muted">Category:</span> <span class="fw-semibold">Payment</span></div>
                                    <div class="col-6 small"><span class="text-muted">Created:</span> <span class="fw-semibold">2 hours ago</span></div>
                                    <div class="col-6 small"><span class="text-muted">Last Update:</span> <span class="fw-semibold">1 hour ago</span></div>
                                </div>
                                <p class="text-muted small mb-3">I'm unable to complete my payment for the premium subscription. The transaction keeps failing with error code 5001.</p>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-primary btn-sm flex-fill"><i class="ri-eye-line me-1"></i> View Details</a>
                                    <button class="btn btn-outline-secondary btn-sm"><i class="ri-message-2-line me-1"></i> Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- In Progress Ticket -->
                    <div class="col-12">
                        <div class="card shadow-sm border-start border-4 border-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="mb-1">Account Verification Problem</h5>
                                        <div class="text-muted small">Ticket #TKT-2024-002</div>
                                    </div>
                                    <div>
                                        <span class="badge bg-info">In Progress</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 small"><span class="text-muted">Priority:</span> <span class="fw-semibold text-warning">Medium</span></div>
                                    <div class="col-6 small"><span class="text-muted">Category:</span> <span class="fw-semibold">Account</span></div>
                                    <div class="col-6 small"><span class="text-muted">Created:</span> <span class="fw-semibold">1 day ago</span></div>
                                    <div class="col-6 small"><span class="text-muted">Last Update:</span> <span class="fw-semibold">3 hours ago</span></div>
                                </div>
                                <p class="text-muted small mb-3">My account verification is stuck in pending status for over 48 hours. I've uploaded all required documents.</p>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-primary btn-sm flex-fill"><i class="ri-eye-line me-1"></i> View Details</a>
                                    <button class="btn btn-outline-secondary btn-sm"><i class="ri-message-2-line me-1"></i> Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resolved Ticket -->
                    <div class="col-12">
                        <div class="card shadow-sm border-start border-4 border-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="mb-1">Task Submission Issue</h5>
                                        <div class="text-muted small">Ticket #TKT-2024-003</div>
                                    </div>
                                    <div>
                                        <span class="badge bg-success">Resolved</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 small"><span class="text-muted">Priority:</span> <span class="fw-semibold text-success">Low</span></div>
                                    <div class="col-6 small"><span class="text-muted">Category:</span> <span class="fw-semibold">Tasks</span></div>
                                    <div class="col-6 small"><span class="text-muted">Created:</span> <span class="fw-semibold">3 days ago</span></div>
                                    <div class="col-6 small"><span class="text-muted">Resolved:</span> <span class="fw-semibold">1 day ago</span></div>
                                </div>
                                <p class="text-muted small mb-3">Unable to submit completed task. The submit button is grayed out and not clickable.</p>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-primary btn-sm flex-fill"><i class="ri-eye-line me-1"></i> View Details</a>
                                    <button class="btn btn-outline-success btn-sm"><i class="ri-thumb-up-line me-1"></i> Rate</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="d-none d-md-block table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ticket ID</th>
                            <th>Subject</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Last Update</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Open Ticket -->
                        <tr>
                            <td><span class="fw-semibold text-primary">#TKT-2024-001</span></td>
                            <td>
                                <div>
                                    <div class="fw-medium">Payment Issue - Transaction Failed</div>
                                    <div class="text-muted small">I'm unable to complete my payment for the premium subscription...</div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark">Payment</span></td>
                            <td><span class="badge bg-danger">High</span></td>
                            <td><span class="badge bg-warning">Open</span></td>
                            <td>2 hours ago</td>
                            <td>1 hour ago</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-primary btn-sm me-1"><i class="ri-eye-line me-1"></i> View</a>
                                <button class="btn btn-outline-secondary btn-sm"><i class="ri-message-2-line me-1"></i> Reply</button>
                            </td>
                        </tr>

                        <!-- In Progress Ticket -->
                        <tr>
                            <td><span class="fw-semibold text-primary">#TKT-2024-002</span></td>
                            <td>
                                <div>
                                    <div class="fw-medium">Account Verification Problem</div>
                                    <div class="text-muted small">My account verification is stuck in pending status...</div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark">Account</span></td>
                            <td><span class="badge bg-warning">Medium</span></td>
                            <td><span class="badge bg-info">In Progress</span></td>
                            <td>1 day ago</td>
                            <td>3 hours ago</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-primary btn-sm me-1"><i class="ri-eye-line me-1"></i> View</a>
                                <button class="btn btn-outline-secondary btn-sm"><i class="ri-message-2-line me-1"></i> Reply</button>
                            </td>
                        </tr>

                        <!-- Resolved Ticket -->
                        <tr>
                            <td><span class="fw-semibold text-primary">#TKT-2024-003</span></td>
                            <td>
                                <div>
                                    <div class="fw-medium">Task Submission Issue</div>
                                    <div class="text-muted small">Unable to submit completed task. The submit button is grayed out...</div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark">Tasks</span></td>
                            <td><span class="badge bg-success">Low</span></td>
                            <td><span class="badge bg-success">Resolved</span></td>
                            <td>3 days ago</td>
                            <td>1 day ago</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-primary btn-sm me-1"><i class="ri-eye-line me-1"></i> View</a>
                                <button class="btn btn-outline-success btn-sm"><i class="ri-thumb-up-line me-1"></i> Rate</button>
                            </td>
                        </tr>

                        <!-- More Open Tickets -->
                        <tr>
                            <td><span class="fw-semibold text-primary">#TKT-2024-004</span></td>
                            <td>
                                <div>
                                    <div class="fw-medium">Withdrawal Request Pending</div>
                                    <div class="text-muted small">My withdrawal request has been pending for over 5 business days...</div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark">Payment</span></td>
                            <td><span class="badge bg-danger">High</span></td>
                            <td><span class="badge bg-warning">Open</span></td>
                            <td>4 hours ago</td>
                            <td>4 hours ago</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-primary btn-sm me-1"><i class="ri-eye-line me-1"></i> View</a>
                                <button class="btn btn-outline-secondary btn-sm"><i class="ri-message-2-line me-1"></i> Reply</button>
                            </td>
                        </tr>

                        <tr>
                            <td><span class="fw-semibold text-primary">#TKT-2024-005</span></td>
                            <td>
                                <div>
                                    <div class="fw-medium">Job Posting Error</div>
                                    <div class="text-muted small">Getting an error when trying to post a new job. Error code: JOB-404...</div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark">Jobs</span></td>
                            <td><span class="badge bg-warning">Medium</span></td>
                            <td><span class="badge bg-info">In Progress</span></td>
                            <td>6 hours ago</td>
                            <td>2 hours ago</td>
                            <td class="text-end">
                                <a href="#" class="btn btn-primary btn-sm me-1"><i class="ri-eye-line me-1"></i> View</a>
                                <button class="btn btn-outline-secondary btn-sm"><i class="ri-message-2-line me-1"></i> Reply</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">5</span> of <span class="fw-semibold">24</span> results
                </div>
                <div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item"><a class="page-link" href="#">5</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newTicketModalLabel">Create New Support Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="subject" class="form-label">Subject *</label>
                                <input type="text" class="form-control" id="subject" placeholder="Brief description of your issue" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select" id="category" required>
                                    <option value="">Select category</option>
                                    <option value="payment">Payment</option>
                                    <option value="account">Account</option>
                                    <option value="tasks">Tasks</option>
                                    <option value="jobs">Jobs</option>
                                    <option value="technical">Technical</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Priority *</label>
                                <select class="form-select" id="priority" required>
                                    <option value="">Select priority</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="attachment" class="form-label">Attachments</label>
                                <input type="file" class="form-control" id="attachment" multiple>
                                <div class="form-text">You can attach screenshots or documents (max 5 files, 5MB each)</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" rows="5" placeholder="Please provide detailed information about your issue..." required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Create Ticket</button>
                </div>
            </div>
        </div>
    </div>
</div>