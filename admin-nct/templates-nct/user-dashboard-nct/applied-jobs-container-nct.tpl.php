<table border="1" align="left" class="common_table">
    <tbody>
        <tr>
            <th>Business Name</th>
            <th>Business Logo</th>
            <th>Job Category</th>
            <th>Job Title</th>
            <th>Job Location</th>
            <th>Employment Type</th>
            <th>Last Date of Application</th>
            <th>Posted On</th>
            <th>Posted By</th>
            <th>Applied On</th>
            <th>Featured</th>
        </tr>
        <?php echo $this->jobs; ?>
    </tbody>
</table>
<div class="pagination-container">
    <?php echo $this->pagination; ?>
</div>
