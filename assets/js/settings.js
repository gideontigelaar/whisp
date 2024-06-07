function editProfile(user_id) {
    var displayName = document.getElementById('edit-display-name').value;
    var profilePicture = document.getElementById('edit-profile-picture').value;
    var bio = document.getElementById('edit-bio').value;
    setButtonLoadingState(['edit-profile-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/edit-user-profile.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            setButtonLoadingState(['edit-profile-button'], false, false);
            if (xhr.status === 200) {
                location.reload();
            } else {
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true);
            }
        }
    }
    xhr.send('user_id=' + user_id + '&display_name=' + displayName + '&profile_picture=' + profilePicture + '&bio=' + bio);
}