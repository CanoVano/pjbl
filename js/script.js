function goBack() {
  window.history.back();
}
function showModal(imgElement) {
  const modal = document.getElementById("imageModal");
  const modalImg = document.getElementById("modalImage");
  const memberName = document.getElementById("memberName");
  const memberFeature = document.getElementById("memberFeature");

  modal.style.display = "block";
  modalImg.src = imgElement.src;
  memberName.textContent = imgElement.getAttribute("data-name");
  memberFeature.textContent = imgElement.getAttribute("data-feature");
}

function closeModal() {
  document.getElementById("imageModal").style.display = "none";
}
