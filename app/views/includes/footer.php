<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        /* Footer container styling */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            background-color: white;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            box-sizing: border-box; /* Include padding in width calculation */
        }

        /* Footer table styling */
        .footer-table {
            width: 90%; /* Ensure the table width is responsive */
            display: none; /* Table is hidden by default */
            text-align: center;
            box-sizing: border-box; /* Ensure padding and borders are included in width */
        }

        /* Footer table cell styling */
        .footer-table td {
            padding: 5px; /* Adjust padding for better spacing */
            vertical-align: middle;
        }

        /* Salary and working hours text styling */
        .footer-table .salary,
        .footer-table .hours {
            font-size: 14px;
        }

        /* Button styling */
        .footer-table button {
            background-color: #4C9EBE;
            color: white;
            padding: 10px 30px; /* Adjusted padding to ensure it fits */
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-size: 14px;
            width: auto; /* Ensure button width adjusts properly */
        }

        /* Estimate text styling */
        .footer-table .estimate {
            font-size: 12px;
            color: #555;
        }

        /* Row 2 styling for better spacing */
        .footer-table tr.row-2 td {
            text-align: left;
            font-size: 12px;
        }

        /* Colored text styling */
        .coloredText {
             color: #8b0000;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .footer-table {
                width: 100%; /* Table takes full width on smaller screens */
                padding: 0 10px; /* Adjust padding on smaller screens */
            }

            .footer-table .salary,
            .footer-table .hours {
                font-size: 12px; /* Reduce font size on smaller screens */
            }

            .footer-table .estimate {
                font-size: 10px; /* Reduce font size for estimate */
            }

            .footer-table button {
                padding: 8px 30px; /* Reduce padding for the button */
                font-size: 12px; /* Reduce font size of the button */
            }

            .footer-table tr.row-2 td {
                font-size: 10px; /* Adjust font size in the row for estimate */
            }
        }
    </style>
</head>
<body>
    <div class="footer">
        <table class="footer-table" id="footer-table">
            <tr>
                <td class="salary">Monthly Salary: <span class="coloredText">Rs.30 000</span> approx</td>
                <td class="hours">Daily Working Hours: <span class="coloredText">2:00</span> approx</td>
                <td class="next-btn">
                    <button id="next-button">Next</button>
                </td>
            </tr>
            <tr class="row-2">
                <td colspan="3" class="estimate">
                    *estimate varies with workload, shifts, timings, and location
                </td>
            </tr>
        </table>
    </div>

    <script>
        const nextButton = document.getElementById('next-button');
        const footerTable = document.getElementById('footer-table');

        // Show the footer table when the page loads (change this logic if needed)
        window.addEventListener('load', function() {
            footerTable.style.display = 'table'; // Make the table visible
        });

        // Move to the next step when the Next button is clicked
        nextButton.addEventListener('click', function() {
            if (step < 3) {
                step++;
                window.updateHeader(); // Update the header dynamically
            }
        });
    </script>
</body>
</html>
