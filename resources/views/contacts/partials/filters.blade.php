<div class="row">
    <div class="col-sm-12">
        <button type="button" class="btn btn-warning d-none" id="clearFilterBtn">Clear</i></button>
        <button type="button" class="btn btn-dark" id="searchBtn"><i class="fa fa-search"></i></button>
        
        <div class="filter-group">
            <label>Gender</label>
            <select class="form-control" id="gender-filter">
                <option value="">All</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Email</label>
            <input type="text" class="form-control" id="email-filter">
        </div>
        <div class="filter-group">
            <label>Name</label>
            <input type="text" class="form-control" id="name-filter">
        </div>
        <div class="filter-group">
            <label>Visibility</label>
            <select class="form-control" id="visibility-filter">
                <option value="">All</option>
                <option value="merged">Merged</option>
                <option value="not_merged">Not Merged</option>
            </select>
        </div>
        <span class="filter-icon" ><i class="fa fa-filter"></i></span>
    </div>
</div>