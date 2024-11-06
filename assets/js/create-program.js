function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('image-preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);

}

//anti refresh 
// window.addEventListener('beforeunload', function (e) {
//     const message = 'Are you sure you want to leave this page? Your changes may not be saved.';
//     e.preventDefault(); 
//     e.returnValue = message; 

//     return message; 
// });
//day exercise
// Create the exercise table only once
const exerciseTable = document.createElement('table');
exerciseTable.className = 'table table-bordered';
const thead = document.createElement('thead');
thead.innerHTML = `
    <tr>
        <th>Exercise</th>
        <th>Set</th>
        <th>Rep</th>
        <th>Time</th>
        <th>Muscle Group</th>
        <th>Action</th>
    </tr>
`;
exerciseTable.appendChild(thead);
const tbody = document.createElement('tbody');
exerciseTable.appendChild(tbody);

// Append the exercise table to the exerciseContainer
document.getElementById('exerciseContainer').appendChild(exerciseTable);

// Function to create a dropdown for muscle groups
function createMuscleGroupDropdown(muscleGroups) {
    const select = document.createElement('select');
    select.className = 'form-select';
    select.addEventListener('change', function() {
        const selectedMuscleGroup = this.value;
        fetchExercisesByMuscleGroup(selectedMuscleGroup);
    });

    muscleGroups.forEach(group => {
        const option = document.createElement('option');
        option.value = group.toLowerCase().replace(/\s+/g, '-'); // Use kebab-case for values
        option.textContent = group;
        select.appendChild(option);
    });

    return select;
}

// Function to fetch muscle groups from the server
function fetchMuscleGroups() {
    return fetch('get-muscle-groups.php') // Update with your actual PHP endpoint
        .then(response => response.json())
        .catch(error => {
            console.error('Error fetching muscle groups:', error);
            return [];
        });
}

function fetchExercisesByMuscleGroup(muscleGroup) {
    const formattedMuscleGroup = muscleGroup.replace(/-/g, ' '); // Convert kebab-case back to normal
    return fetch(`get-exercise-program.php?muscle_group=${encodeURIComponent(formattedMuscleGroup)}`) // Encode the parameter
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return []; // Return an empty array if there's an error
            }
            return data; // Return the array of exercises
        })
        .then(exercises => {
            populateExerciseList(exercises);
        })
        .catch(error => {
            console.error('Error fetching exercises:', error);
        });
}


function populateExerciseList(exercises) {
    const exerciseList = document.getElementById('exerciseList');
    exerciseList.innerHTML = ''; // Clear previous exercises

    if (exercises.length === 0) {
        exerciseList.innerHTML = '<p>No exercises found for this muscle group.</p>';
        return;
    }

    exercises.forEach(exercise => {
        const exerciseItem = document.createElement('div');
        exerciseItem.className = 'form-check';
        exerciseItem.innerHTML = `
            <input class="form-check-input" type="radio" name="exercise" value="${exercise.me_id}" id="exercise-${exercise.me_id}">
            <label class="form-check-label" for="exercise-${exercise.me_id}">
                ${exercise.me_name}
            </label>
        `;
        exerciseList.appendChild(exerciseItem);
    });
}

async function addExerciseRow() {
    const muscleGroups = await fetchMuscleGroups(); // Fetch muscle groups

    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <input type="text" class="form-control exercise-input" placeholder="Exercise">
        </td>
        <td><input type="number" class="form-control"></td>
        <td><input type="number" class="form-control"></td>
        <td><input type="text" class="form-control"></td>
        <td>
            <div>
                <label>
                    <input type="checkbox" class="include-muscle-group"> Include Muscle Group
                </label>
            </div>
            <div class="muscle-group-dropdown" style="display: none;"></div>
        </td>
        <td><button class="btn btn-danger remove-exercise">Remove</button></td>
    `;

    // Append the muscle group dropdown to the new row
    const muscleGroupCell = row.querySelector('div.muscle-group-dropdown');
    muscleGroupCell.appendChild(createMuscleGroupDropdown(muscleGroups));

    // Select checkbox and exercise input
    const checkbox = row.querySelector('.include-muscle-group');
    const exerciseCell = row.querySelector('.exercise-input');

    // Set initial state of the exercise input to editable
    exerciseCell.readOnly = false; // <--- Changed: Input is editable by default

    // Add event listener to show/hide the dropdown based on checkbox state
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            muscleGroupCell.style.display = 'block'; // Show the muscle group dropdown
            exerciseCell.readOnly = true; // <--- Changed: Disable exercise input when muscle group is selected
            exerciseCell.value = ''; // Clear the exercise input
        } else {
            muscleGroupCell.style.display = 'none'; // Hide the dropdown
            exerciseCell.readOnly = false; // <--- Changed: Allow typing in the exercise input
        }
    });

    // Add event listener for the exercise cell click
    exerciseCell.addEventListener('click', function() {
        // Fetch exercises based on the selected muscle group when the cell is clicked, only if checkbox is checked
        if (checkbox.checked) {
            const selectedMuscleGroup = muscleGroupCell.querySelector('select').value;
            fetchExercisesByMuscleGroup(selectedMuscleGroup); // Fetch exercises based on selected muscle group
            $('#exerciseSelectionModal').modal('show'); // Show the modal
        }
    });

    // Event listener for exercise selection confirmation
    document.getElementById('confirmExercise').addEventListener('click', function() {
        const selectedExercise = document.querySelector('input[name="exercise"]:checked');
        if (selectedExercise) {
            const exerciseId = selectedExercise.value;
            const exerciseName = selectedExercise.nextElementSibling.textContent; // Get exercise name from label
            exerciseCell.value = exerciseName; // Set selected exercise name
            $('#exerciseSelectionModal').modal('hide'); // Hide modal
        } else {
            alert('Please select an exercise');
        }
    });

    tbody.appendChild(row);

    // Add event listener for the newly created remove button
    row.querySelector('.remove-exercise').addEventListener('click', function() {
        tbody.removeChild(row); // Remove the specific row
    });
}

// Add event listener for the Add Exercise button
document.getElementById('addExercise').addEventListener('click', function() {
    addExerciseRow();
});

// Add event listener for the Clear All button
document.getElementById('removeExercise').addEventListener('click', function() {
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild); // Remove all rows from tbody
    }
});

//end of create exercise day

//week and title
document.addEventListener('DOMContentLoaded', function () {
    const programDurationInput = document.getElementById('program_duration');
    const weekTableContainer = document.getElementById('week-table-container');
    let programDuration = 0;
    let selectedWeek = 1;
    let selectedDay = "Monday";

    // Function to update the modal header and display week/day
    function updateModalHeader(week, day) {
        selectedWeek = week;
        selectedDay = day;

        const selectedWeekDay = document.getElementById('selectedWeekDay');
        selectedWeekDay.textContent = `Week ${week} - ${day}`;
    }

    // Function to update the week table with day buttons
    function updateWeekTable() {
        weekTableContainer.innerHTML = ''; // Clear existing table
        
        if (programDuration > 0) {
            // Create table element
            const table = document.createElement('table');
            table.className = 'table table-bordered';
            
            // Create thead
            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');
            const weekHeader = document.createElement('th');
            weekHeader.textContent = 'Weeks';
            headerRow.appendChild(weekHeader);
            thead.appendChild(headerRow);
            table.appendChild(thead);

            // Create tbody
            const tbody = document.createElement('tbody');
            
            // Add week titles and day buttons to table rows
            for (let i = 1; i <= programDuration; i++) {
                const row = document.createElement('tr');
                const cell = document.createElement('td');

                // Week title
                const weekTitle = document.createElement('h5');
                weekTitle.textContent = 'Week ' + i;
                weekTitle.style.marginBottom = '10px'; // Add margin to separate title from buttons
                cell.appendChild(weekTitle);

                // Days of the week as buttons with unique IDs
                const daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                daysOfWeek.forEach(day => {
                    const dayButton = document.createElement('button');
                    dayButton.className = 'btn btn-outline-primary mr-2 mb-2'; // Outline button with margin
                    dayButton.textContent = day;
                    dayButton.type = 'button'; // Prevent form submission
                    dayButton.id = `w${i}${day.toLowerCase()}`; // Unique ID e.g., "w1monday"
                    dayButton.setAttribute('data-bs-toggle', 'modal');
                    dayButton.setAttribute('data-bs-target', '#dayModal');

                    // Add event listener to update modal header and pass correct week/day
                    dayButton.addEventListener('click', function() {
                        updateModalHeader(i, day);
                    });

                    cell.appendChild(dayButton);
                });

                row.appendChild(cell);
                tbody.appendChild(row);
            }

            table.appendChild(tbody);
            weekTableContainer.appendChild(table);
        }
    }

    // Event listener to update the week table whenever the program duration changes
    document.getElementById('increment_duration').addEventListener('click', function () {
        programDuration++;
        programDurationInput.value = programDuration;
        updateWeekTable();
    });

    document.getElementById('decrement_duration').addEventListener('click', function () {
        if (programDuration > 0) {
            programDuration--;
            programDurationInput.value = programDuration;
            updateWeekTable();
        }
    });
});

// Save the day plan when the "Save Plan" button is clicked
document.getElementById('saveDayPlan').addEventListener('click', function () {
    const dayTitle = document.getElementById('dayTitle').value;

    if (dayTitle.trim() === '') {
        alert('Please enter a title for this day.');
        return;
    }

    const exerciseTable = document.getElementById('exerciseContainer').querySelector('table');
    if (!exerciseTable) {
        alert('Please add at least one exercise.');
        return;
    }

    // Clone the exercise table to append it to the weekly plan
    const clonedTable = exerciseTable.cloneNode(true);

    // Create a section for this day in the weekly plan
    const weeklyPlanContainer = document.getElementById('week-table-container');
    const daySection = document.createElement('div');
    daySection.classList.add('day-section', 'mb-3');

    // Append the day title and week/day to the section
    const dayTitleElement = document.createElement('h5');
    dayTitleElement.textContent = `Week ${selectedWeek} - ${selectedDay}: ${dayTitle}`;
    daySection.appendChild(dayTitleElement);

    // Append the cloned exercise table
    daySection.appendChild(clonedTable);

    // Add the day section to the weekly plan
    weeklyPlanContainer.appendChild(daySection);

    // Reset modal content for next day
    document.getElementById('dayTitle').value = '';
    document.getElementById('exerciseContainer').innerHTML = ''; // Clear exercises for the next day
});

// Function to create a dropdown for exercises based on the selected muscle group
function fetchExercisesByMuscleGroup(muscleGroup) {
    console.log("Fetching exercises for muscle group:", muscleGroup); // Debug log
    return fetch(`get-exercise.php?muscle_group=${muscleGroup}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return []; // Return an empty array if there's an error
            }
            return data; // Return the array of exercises
        })
        .then(exercises => {
            populateExerciseList(exercises);
        })
        .catch(error => {
            console.error('Error fetching exercises:', error);
        });
}

// Function to create a dropdown for muscle groups
function createMuscleGroupDropdown(muscleGroups) {
    const select = document.createElement('select');
    select.className = 'form-select';
    select.addEventListener('change', function() {
        const selectedMuscleGroup = this.value;
        fetchExercisesByMuscleGroup(selectedMuscleGroup); // Fetch exercises for selected muscle group
    });

    muscleGroups.forEach(group => {
        const option = document.createElement('option');
        option.value = group.toLowerCase().replace(/\s+/g, '-'); // Convert to kebab-case
        option.textContent = group;
        select.appendChild(option);
    });

    return select;
}


