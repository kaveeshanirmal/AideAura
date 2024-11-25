CREATE TABLE complaints (
    complaintID INT AUTO_INCREMENT PRIMARY KEY,
    customerID INT NOT NULL,
    issue_type VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('Pending', 'In Progress', 'Resolved') DEFAULT 'Pending',
    priority ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customerID) REFERENCES customer(customerID) ON DELETE CASCADE
);

CREATE TABLE complaint_updates (
    updateID INT AUTO_INCREMENT PRIMARY KEY,
    complaintID INT NOT NULL,
    status ENUM('Pending', 'In Progress', 'Resolved'),
    comments TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (complaintID) REFERENCES complaints(complaintID) ON DELETE CASCADE
);
