<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Selection Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Search for a worker</h2>
    <form id="jobForm" action="<?=ROOT?>/public/SearchForWorker/find" method="POST" enctype="multipart/form-data">
    <label for="start-time">Start Time:</label>
        <select id="start-time" name="start-time"></select>

        <label for="end-time">End Time:</label>
        <select id="end-time" name="end-time"></select>

        <label for="location">Location:</label>
        <select id="location" name="location">
            <option value="">Select City</option>
        </select>

        <label for="job-type">Job Type:</label>
        <select id="job-type" name="job-type">
            <option value="cook">Cook</option>
            <option value="nanny">Nanny</option>
            <option value="maid">Maid</option>
            <option value="24-hour-cooking">24-Hour Cooking</option>
            <option value="all-rounder">All-Rounder</option>
        </select>

        <button type="submit">Submit</button>
    </form>
</div>

<script>
    // Populate time dropdowns
    function populateTimeDropdown(id) {
        let select = document.getElementById(id);
        for (let hour = 0; hour < 24; hour++) {
            for (let min = 0; min < 60; min += 30) {
                let time = (hour < 10 ? "0" : "") + hour + ":" + (min === 0 ? "00" : min);
                let option = document.createElement("option");
                option.value = time;
                option.textContent = time;
                select.appendChild(option);
            }
        }
    }
    populateTimeDropdown("start-time");
    populateTimeDropdown("end-time");

    // List of cities in Sri Lanka
    const cities = [
        "Colombo", "Kandy", "Galle", "Jaffna", "Negombo", "Anuradhapura", "Ratnapura",
        "Badulla", "Trincomalee", "Batticaloa", "Matara", "Kurunegala", "Gampaha", "Nuwara Eliya",
        "Polonnaruwa", "Hambantota", "Mannar", "Vavuniya", "Kilinochchi", "Puttalam", "Monaragala",
        "Matale", "Kalutara", "Ampara", "Mullaitivu", "Kegalle", "Chilaw", "Dambulla", "Hatton",
        "Wattala", "Dehiwala", "Moratuwa", "Panadura", "Gampola", "Beruwala", "Horana", "Ja-Ela",
        "Weligama", "Ambalangoda", "Kelaniya", "Balangoda", "Maharagama", "Avissawella", "Kuliyapitiya",
        "Embilipitiya", "Kataragama", "Kadawatha", "Homagama", "Mawanella", "Minuwangoda", "Tangalle"
    ];


    let locationSelect = document.getElementById("location");
    cities.forEach(city => {
        let option = document.createElement("option");
        option.value = city.toLowerCase();
        option.textContent = city;
        locationSelect.appendChild(option);
    });

    // // Form submission
    // document.getElementById("jobForm").addEventListener("submit", function(event) {
    //     event.preventDefault();
    //     alert("Form submitted successfully!");
    // });
</script>

</body>
</html>
