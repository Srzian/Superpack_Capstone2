<!DOCTYPE html>
<html>
<head>
    <title>Edit Attendance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background-color: #383C3F; color: white; padding: 10px; }
        .navbar a { color: white; padding: 15px; text-decoration: none; }
        .active { font-weight: bold; }
        .card { background: #FEFEFE; border-radius: 10px; padding: 15px; box-shadow: 5px 5px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-gray-200">

    <!-- Header Navigation -->
    <div class="header flex justify-between items-center">
        <div class="navbar"
            <a href="#" class="active">Dashboard</a>
            <a href="#">People</a>
            <a href="#">HR</a>
            <a href="#">Attendance</a>
            <a href="#">Recruitment</a>
            <a href="#">Reports</a>
            <a href="#">Settings</a>
        </div>
        <div>
            <label>Email Notification:</label>
            <input type="checkbox" id="emailNotification">
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="m-4 text-gray-600">
        Dashboard > Attendance > Edit Attendance
    </div>

   
 

    <!-- Attendance Table -->
    <div class="card mx-4">
        <table class="w-full border-collapse border border-white-300">
            <thead class="bg-gray-200">
		 <!-- Date and Employee Selector -->
		<div class="m-4 flex space-x-4">
        <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>">
        <select class="p-2 border rounded">
            <option value="4309">Gussie Kuhic (EmpID: 4309)</option>
        </select>
    </div>
                <tr>
                    <th class="border p-2">Name</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Shift</th>
                    <th class="border p-2">Clock-In/Out Time</th>
                    <th class="border p-2">Activity</th>
                    <th class="border p-2">Save</th>
                </tr>
            </thead>
            <tbody>
                <!-- Employee Attendance Row -->
                <tr class="bg-white">
                    <td class="border p-2">4309<br>Gussie Kuhic</td>
                    <td class="border p-2"><span class="bg-green-500 px-2 py-1 rounded">P</span></td>
                    <td class="border p-2">
                        <b>Shift:</b> Day<br>
                        <b>Start Time:</b> 9:30 AM<br>
                        <b>End Time:</b> 6:30 PM
                    </td>
                    <td class="border p-2">
					<button class="bg-green-500 text-white px-2 py-1 rounded">Marked</button>  
                        <b>Clock In IP:</b> Not Set<br>
                        <b>Clock Out IP:</b> Not Set<br>
                        <b>Working From:</b> Office<br>
                        <b>Notes</b>: Not Set<br>
                    </td>
                    <td class="border p-2">
                        <div class="mb-2">
                            <b>Day</b><br>
                            Clock In: <input type="time" value="09:30" class="border p-1">
                            <button class="bg-green-500 text-white px-2 py-1 rounded">Clock In Image</button>
                        </div>
                        <div>
                            Clock Out: <input type="time" value="18:30" class="border p-1">
                            <button class="bg-green-500 text-white px-2 py-1 rounded">Clock Out Image</button>
                        </div>
                    </td>
                    <td class="border p-2">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded">✔</button>
                    </td>
                </tr>
				 <tr class="bg-white">
                    <td class="border p-2">4309<br>Gussie Kuhic</td>
                    <td class="border p-2"><span class="bg-green-500 px-2 py-1 rounded">P</span></td>
                    <td class="border p-2">
                        <b>Shift:</b> Day<br>
                        <b>Start Time:</b> 9:30 AM<br>
                        <b>End Time:</b> 6:30 PM
                    </td>
                    <td class="border p-2">
					<button class="bg-green-500 text-white px-2 py-1 rounded">Marked</button>  
                        <b>Clock In IP:</b> Not Set<br>
                        <b>Clock Out IP:</b> Not Set<br>
                        <b>Working From:</b> Office<br>
                        <b>Notes</b>: Not Set<br>
                    </td>
                    <td class="border p-2">
                        <div class="mb-2">
                            <b>Day</b><br>
                            Clock In: <input type="time" value="09:30" class="border p-1">
                            <button class="bg-green-500 text-white px-2 py-1 rounded">Clock In Image</button>
                        </div>
                        <div>
                            Clock Out: <input type="time" value="18:30" class="border p-1">
                            <button class="bg-green-500 text-white px-2 py-1 rounded">Clock Out Image</button>
                        </div>
                    </td>
                    <td class="border p-2">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded">✔</button>
                    </td>
                </tr>
				 <tr>
               <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
				 <td class="border p-10"></td>
            </tr>
			 <tr>
             <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
				 <td class="border p-10"></td>
            </tr>
			 <tr>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
				 <td class="border p-10"></td>
            </tr>
			 <tr>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
				 <td class="border p-10"></td>
            </tr>
			 <tr>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
                <td class="border p-10"></td>
				 <td class="border p-10"></td>
            </tr>
			 
		
          	
				
			
            </tbody>
        </table>
    </div>

</body>
</html>