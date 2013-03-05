<div class="center">

  <p><span class="bold">{@lang('Reporter:')@}</span> {{ $oAvatarDesign->get($oUserModel->getUsername($report->reporterId), $oUserModel->getFirstName($report->reporterId) ,null, 64) }}</p>
  <p><span class="bold">{@lang('Spammer:')@}</span> {{ $oAvatarDesign->get($oUserModel->getUsername($report->spammerId), $oUserModel->getFirstName($report->spammerId) ,null, 64) }}</p>
  <p><span class="bold">{@lang('Contant Type:')@}</span> <span class="italic">{% $report->contentType %}</span></p>
  <p><span class="bold">{@lang('URL:')@}</span> <span class="italic"><a href="{% $report->url %}" target="_blank">{% $report->url %}</a></span></p>
  <p><span class="bold">{@lang('Description of report')@}</span> <span class="italic">{% $report->description %}</span></p>
  <p><span class="bold">{@lang('Date:')@}</span><span class="italic">{% $dateTime->get($report->dateTime)->dateTime() %}</span></p>

  <div class="m_button inline">{{ LinkCoreForm::display(t('Delete Report'), PH7_ADMIN_MOD, 'report', 'delete', array('id'=>$report->reportId)) }}</div>

</div>