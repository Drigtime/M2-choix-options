// Récupération des éléments HTML
const studentsList = document.getElementById("students-list");
const coursesList = document.getElementById("courses-list");
const currentCourseList = document.getElementById("current-course-list");
const toRightButton = document.getElementById("to-right");
const toLeftButton = document.getElementById("to-left");
const form = document.getElementById("form-step-2");

// Création d'un tableau associatif pour stocker les étudiants pour chaque parcours
// get data-form-data="{"1":["33","32","36","35"],"6":["34","40","37"],"7":["39","31","41"]}"
const courses = JSON.parse(form.dataset.formData);

// Fonction de mise à jour de la liste des étudiants dans le parcours sélectionné
function updateCurrentCourseList() {
    // Suppression de tous les éléments de la liste
    currentCourseList.innerHTML = "";

    // Récupération de l'identifiant du parcours sélectionné
    const selectedCourse = coursesList.value;

    // Récupération des étudiants pour le parcours sélectionné
    const students = courses[selectedCourse] || [];

    // Ajout des étudiants à la liste
    students
        .forEach((student) => {
        const option = document.createElement("option");
        option.value = student.value;
        option.text = student.text;
        currentCourseList.appendChild(option);
    });

    updateStudentsList();
}

function updateStudentsList() {
    // reorder students list
    const students = Array.from(studentsList.options).sort((a, b) => {
        if (a.text < b.text) {
            return -1;
        }
        if (a.text > b.text) {
            return 1;
        }
        return 0;
    });

    // clear students list
    studentsList.innerHTML = "";

    // add students to students list
    students.forEach((student) => {
        studentsList.appendChild(student);
    });
}

// Écouteur d'événement pour la sélection d'un parcours
coursesList.addEventListener("change", updateCurrentCourseList);

// Fonction pour ajouter un étudiant au parcours sélectionné
function addStudentsToCourse() {
    // Récupération des étudiants sélectionnés
    const selectedStudents = Array.from(studentsList.selectedOptions).map(
        (option) => ({value: option.value, text: option.text})
    );

    // Récupération de l'identifiant du parcours sélectionné
    const selectedCourse = coursesList.value;

    // Ajout des étudiants à la liste correspondante
    if (!courses[selectedCourse]) {
        courses[selectedCourse] = [];
    }
    courses[selectedCourse] = [
        ...new Set(courses[selectedCourse].concat(selectedStudents)),
    ];

    // Suppression des étudiants de la liste de gauche
    selectedStudents.forEach((student) => {
        const option = studentsList.querySelector(`option[value="${student.value}"]`);
        option.remove();
    });

    // Mise à jour de la liste des étudiants dans le parcours sélectionné
    updateCurrentCourseList();
}

// Fonction pour retirer un étudiant du parcours sélectionné
function removeStudentsFromCourse() {
    // Récupération des étudiants sélectionnés
    const selectedStudents = Array.from(currentCourseList.selectedOptions).map(
        (option) => ({value: option.value, text: option.text})
    );

    // Récupération de l'identifiant du parcours sélectionné
    const selectedCourse = coursesList.value;

    // Suppression des étudiants de la liste correspondante
    courses[selectedCourse] = courses[selectedCourse].filter(
        (student) =>  !selectedStudents.some((selectedStudent) => selectedStudent.value === student.value)
    );

    // Ajout des étudiants à la liste de gauche
    selectedStudents
        .forEach((student) => {
        const option = document.createElement("option");
        option.value = student.value;
        option.text = student.text;
        studentsList.appendChild(option);
    });

    // Mise à jour de la liste des étudiants dans le parcours sélectionné
    updateCurrentCourseList();
}

form.addEventListener("submit", function (event) {
    // Empêche l'envoi du formulaire
    event.preventDefault();

    // Check if there are still students in the left list
    if (studentsList.options.length > 0) {
        alert("Il reste des étudiants à placer dans les parcours.");
        return;
    }

    // Ajout des étudiants pour chaque parcours au formulaire
    Object.keys(courses).forEach((course) => {
        courses[course].forEach((student) => {
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = `parcours[${course}][]`;
            input.value = student.value;
            form.appendChild(input);
        });
    });

    // Envoi du formulaire
    form.submit();
});

// Écouteurs d'événement pour les boutons de déplacement
toRightButton.addEventListener("click", addStudentsToCourse);
toLeftButton.addEventListener("click", removeStudentsFromCourse);

Object.keys(courses).forEach((course) => {
    courses[course].forEach((student) => {
        const option = studentsList.querySelector(`option[value="${student.value}"]`);
        option.remove();
    });
});

// Mise à jour initiale de la liste des étudiants dans le parcours sélectionné
updateCurrentCourseList();
