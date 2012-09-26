<form class="year_form" action="faculty/{{ fac.eid  }}/citation/{{ citation.id  }}/form_year" method="post">

	<input type="text" class="span1" name="year" value="{{ citation.year }}">
	<input type="hidden" name="redirect" value="{{ redirect }}">
	<input type="submit" class="btn btn-success" value="update">
</form>


