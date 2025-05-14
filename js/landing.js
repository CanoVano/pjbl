<script>
  const links = document.querySelectorAll('.navbar-center a');
  links.forEach(link => {
    if (link.href === window.location.href) {
      link.classList.add('active');
    }
  });
</script>
