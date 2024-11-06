// draggable.js
document.addEventListener("DOMContentLoaded", function () {
  const draggable = document.getElementById("draggable");

  if (!draggable) return; // Exit if the element is not found

  // Retrieve the last position from local storage
  const lastPosition = JSON.parse(localStorage.getItem("draggablePosition"));
  if (lastPosition) {
    draggable.style.left = `${lastPosition.left}px`;
    draggable.style.top = `${lastPosition.top}px`;
  } else {
    // Set default position if not found in local storage
    draggable.style.left = "20px";
    draggable.style.top = "20px";
  }

  let isDragging = false;
  let offsetX, offsetY;

  draggable.addEventListener("mousedown", function (e) {
    isDragging = true;
    // Calculate the offset between mouse pointer and the element's top-left corner
    offsetX = e.clientX - draggable.getBoundingClientRect().left;
    offsetY = e.clientY - draggable.getBoundingClientRect().top;

    // Add event listeners for dragging and releasing
    document.addEventListener("mousemove", onMouseMove);
    document.addEventListener("mouseup", onMouseUp);
  });

  function onMouseMove(e) {
    if (isDragging) {
      const x = e.clientX - offsetX;
      const y = e.clientY - offsetY;

      // Update the position of the element
      draggable.style.left = `${x}px`;
      draggable.style.top = `${y}px`;

      // Store the current position in local storage
      localStorage.setItem("draggablePosition", JSON.stringify({ left: x, top: y }));
    }
  }

  function onMouseUp() {
    isDragging = false;
    // Remove the event listeners when dragging stops
    document.removeEventListener("mousemove", onMouseMove);
    document.removeEventListener("mouseup", onMouseUp);
  }
});
