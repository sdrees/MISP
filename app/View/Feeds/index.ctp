<div class="feed index">
    <h2><?php echo __('Feeds');?></h2>
        <b><?php echo __('Generate feed lookup caches or fetch feed data (enabled feeds only)');?></b>
        <div class="toggleButtons">
            <a href="<?php echo $baseurl; ?>/feeds/cacheFeeds/all" class="toggle-left qet btn btn-inverse"><?php echo __('Cache all feeds');?></a>
            <a href="<?php echo $baseurl; ?>/feeds/cacheFeeds/freetext" class="toggle qet btn btn-inverse"><?php echo __('Cache freetext/CSV feeds');?></a>
            <a href="<?php echo $baseurl; ?>/feeds/cacheFeeds/misp" class="toggle-right qet btn btn-inverse"><?php echo __('Cache MISP feeds');?></a>
            <a href="<?php echo $baseurl; ?>/feeds/fetchFromAllFeeds" class="btn btn-primary qet" style="margin-left:20px;"><?php echo __('Fetch and store all feed data');?></a>
        </div><br />
    <div class="pagination">
        <ul>
        <?php
        $this->Paginator->options(array(
            'update' => '.span12',
            'evalScripts' => true,
            'before' => '$(".progress").show()',
            'complete' => '$(".progress").hide()',
        ));

            echo $this->Paginator->prev('&laquo; ' . __('previous'), array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'prev disabled', 'escape' => false, 'disabledTag' => 'span'));
            echo $this->Paginator->numbers(array('modulus' => 20, 'separator' => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'span'));
            echo $this->Paginator->next(__('next') . ' &raquo;', array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'next disabled', 'escape' => false, 'disabledTag' => 'span'));
        ?>
        </ul>
    </div>
    <?php
        $canViewFeedData = $isSiteAdmin || intval(Configure::read('MISP.host_org_id')) === $me['org_id'];
        $data = array(
            'children' => array(
                array(
                    'children' => array(
                        array(
                            'class' => 'hidden mass-select',
                            'text' => __('Enable selected'),
                            'onClick' => "multiSelectToggleFeeds",
                            'onClickParams' => array('1', '0')
                        ),
                        array(
                            'class' => 'hidden mass-select',
                            'text' => __('Disable selected'),
                            'onClick' => "multiSelectToggleFeeds",
                            'onClickParams' => array('0', '0')
                        ),
                        array(
                            'class' => 'hidden mass-select',
                            'text' => __('Enable caching for selected'),
                            'onClick' => "multiSelectToggleFeeds",
                            'onClickParams' => array('1', '1')
                        ),
                        array(
                            'class' => 'hidden mass-select',
                            'text' => __('Disable caching for selected'),
                            'onClick' => "multiSelectToggleFeeds",
                            'onClickParams' => array('0', '1')
                        ),
                    )
                ),
                array(
                    'children' => array(
                        array(
                            'url' => '/feeds/index/scope:default',
                            'text' => __('Default feeds'),
                            'active' => $scope === 'default'
                        ),
                        array(
                            'url' => '/feeds/index/scope:custom',
                            'text' => __('Custom feeds'),
                            'active' => $scope === 'custom'
                        ),
                        array(
                            'url' => '/feeds/index/scope:all',
                            'text' => __('All feeds'),
                            'active' => $scope === 'all'
                        ),
                        array(
                            'url' => '/feeds/index/scope:enabled',
                            'text' => __('Enabled feeds'),
                            'active' => $scope === 'enabled'
                        )
                    )
                )
            )
        );
        echo $this->element('/genericElements/ListTopBar/scaffold', array('data' => $data));
    ?>
    <table class="table table-striped table-hover table-condensed">
    <tr>
            <?php if ($isSiteAdmin): ?>
                <th>
                    <input class="select_all select" type="checkbox" title="<?php echo __('Select all');?>" role="button" tabindex="0" aria-label="<?php echo __('Select all events on current page');?>" onClick="toggleAllCheckboxes();" />&nbsp;
                </th>
            <?php else: ?>
                <th style="padding-left:0px;padding-right:0px;">&nbsp;</th>
            <?php endif;?>
            <th><?php echo $this->Paginator->sort('id');?></th>
            <th title="<?php echo __('Enable pulling the feed into your MISP as events/attributes.'); ?>"><?php echo $this->Paginator->sort('enabled');?></th>
            <th title="<?php echo __('Enable caching the feed into Redis - allowing for correlations to the feed to be shown.'); ?>"><?php echo $this->Paginator->sort('caching_enabled', __('Caching enabled'));?></th>
            <th><?php echo $this->Paginator->sort('name');?></th>
            <th><?php echo $this->Paginator->sort('source_format', __('Feed Format'));?></th>
            <th><?php echo $this->Paginator->sort('provider', __('Provider'));?></th>
            <th><?php echo $this->Paginator->sort('input_source', __('Input'));?></th>
            <th><?php echo $this->Paginator->sort('url', __('URL'));?></th>
            <th><?php echo $this->Paginator->sort('headers');?></th>
            <th><?php echo __('Target');?></th>
            <th><?php echo __('Publish');?></th>
            <th><?php echo __('Delta Merge');?></th>
            <th><?php echo __('Override IDS');?></th>
            <th><?php echo $this->Paginator->sort('distribution');?></th>
            <th><?php echo $this->Paginator->sort('tag');?></th>
            <th><?php echo $this->Paginator->sort('lookup_visible', __('Lookup visible'));?></th>
            <th class="actions"><?php echo __('Caching');?></th>
            <th class="actions"><?php echo __('Actions');?></th>
    </tr><?php
foreach ($feeds as $item):
    $rules = array();
    $rules = json_decode($item['Feed']['rules'], true);
    $fieldOptions = array('tags', 'orgs');
    $typeOptions = array('OR' => array('colour' => 'green', 'text' => 'allowed'), 'NOT' => array('colour' => 'red', 'text' => 'blocked'));
    $ruleDescription = '';
    foreach ($fieldOptions as $fieldOption) {
        foreach ($typeOptions as $typeOption => $typeData) {
            if (isset($rules[$fieldOption][$typeOption]) && !empty($rules[$fieldOption][$typeOption])) {
                $ruleDescription .= '<span class=\'bold\'>' .
                ucfirst($fieldOption) . ' ' .
                $typeData['text'] . '</span>: <span class=\'' .
                $typeData['colour'] . '\'>';
                foreach ($rules[$fieldOption][$typeOption] as $k => $temp) {
                    if ($k != 0) $ruleDescription .= ', ';
                    $ruleDescription .= h($temp);
                }
                $ruleDescription .= '</span><br />';
            }
        }
    }
?>
    <tr>
        <?php
            if ($isSiteAdmin):
        ?>
                <td style="width:10px;" data-id="<?php echo h($item['Feed']['id']); ?>">
                    <input class="select" type="checkbox" data-id="<?php echo $item['Feed']['id'];?>" aria-label="select <?php echo $item['Feed']['name'];?>" />
                </td>
        <?php
            else:
        ?>
                <td style="padding-left:0px;padding-right:0px;"></td>
        <?php
            endif;
        ?>
        <td class="short">
            <?php
                if ($canViewFeedData) {
                    echo sprintf(
                        '<a href="%s/feeds/view/%s" title="%s">%s</a>',
                        $baseurl,
                        h($item['Feed']['id']),
                        sprintf(
                            __('View feed #%s', h($item['Feed']['id']))
                        ),
                        h($item['Feed']['id'])
                    );
                } else {
                    echo h($item['Feed']['id']);
                }

            ?>
        </td>
        <td class="short">
            <span role="img" <?php echo ($item['Feed']['enabled'] ? 'class="icon-ok" aria-label="Yes"' : 'class="icon-remove" aria-label="No"'); ?>"></span>
            <span
                class="short <?php if (!$item['Feed']['enabled'] || empty($ruleDescription)) echo "hidden"; ?>"
                data-toggle="popover"
                title="Filter rules"
                data-content="<?php echo $ruleDescription; ?>"
            >
                (<?php echo __('Rules');?>)
            </span>
        </td>
        <td class="short">
            <span role="img" <?php echo ($item['Feed']['caching_enabled'] ? 'class="icon-ok" aria-label="Yes"' : 'class="icon-remove" aria-label="No"'); ?>"></span>
        </td>
        <td>
            <?php
                echo h($item['Feed']['name']);
                if ($item['Feed']['default']):
            ?>
                    <img src="<?php echo $baseurl;?>/img/orgs/MISP.png" width="24" height="24" style="padding-bottom:3px;" />
            <?php
                endif;
            ?>
        </td>
        <td><?php echo $feed_types[$item['Feed']['source_format']]['name']; ?>&nbsp;</td>
        <td><?php echo h($item['Feed']['provider']); ?>&nbsp;</td>
        <td><?php echo h($item['Feed']['input_source']); ?>&nbsp;</td>
        <td><?php echo h($item['Feed']['url']); ?>&nbsp;</td>
        <td class="short"><?php echo nl2br(h($item['Feed']['headers'])); ?>&nbsp;</td>
        <td class="shortish">
        <?php
            if (in_array($item['Feed']['source_format'], array('freetext', 'csv'))):
                if ($item['Feed']['fixed_event']):
                    if (isset($item['Feed']['event_error'])):
                ?>
                    <span class="red bold"><?php echo __('Error: Invalid event!');?></span>
                <?php
                    else:
                        if ($item['Feed']['event_id']):
                        ?>
                            <a href="<?php echo $baseurl;?>/events/view/<?php echo h($item['Feed']['event_id']); ?>"><?php echo __('Fixed event %s', h($item['Feed']['event_id']));?></a>
                        <?php
                        else:
                            echo __('New fixed event');
                        endif;
                    endif;
                endif;
            else:
                echo ' ';
            endif;
         ?>
        </td>
        <?php
            if ($item['Feed']['source_format'] != 'misp'):
        ?>
                <td><span role="img" <?php echo ($item['Feed']['publish'] ? 'class="icon-ok" aria-label="Yes"' : 'class="icon-remove" aria-label="No"'); ?>"></span></td>
                <td><span role="img" <?php echo ($item['Feed']['delta_merge'] ? 'class="icon-ok" aria-label="Yes"' : 'class="icon-remove" aria-label="No"'); ?>"></span></td>
                <td><span role="img" <?php echo ($item['Feed']['override_ids'] ? 'class="icon-ok" aria-label="Yes"' : 'class="icon-remove" aria-label="No"'); ?>"></span></td>
        <?php
            else:
        ?>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
        <?php
            endif;
        ?>
        <td <?php if ($item['Feed']['distribution'] == 0) echo 'class="red"'; ?>>
        <?php
            echo $item['Feed']['distribution'] == 4 ? '<a href="' . $baseurl . '/sharing_groups/view/' . h($item['SharingGroup']['id']) . '">' . h($item['SharingGroup']['name']) . '</a>' : $distributionLevels[$item['Feed']['distribution']] ;
        ?>
        </td>
        <td>
        <?php if ($item['Feed']['tag_id']): ?>
            <a href="<?php echo $baseurl;?>/events/index/searchtag:<?php echo h($item['Tag']['id']); ?>" class=tag style="background-color:<?php echo h($item['Tag']['colour']);?>;color:<?php echo $this->TextColour->getTextColour($item['Tag']['colour']);?>"><?php echo h($item['Tag']['name']); ?></a>
        <?php else: ?>
            &nbsp;
        <?php endif;?>
        </td>
        <td class="short"><span role="img" <?php echo ($item['Feed']['lookup_visible'] ? 'class="icon-ok" aria-label="Yes"' : 'class="icon-remove" aria-label="No"'); ?>"></span>
        <td class="short action-links <?php echo !empty($item['Feed']['cache_timestamp']) ? 'bold' : 'bold red';?>">
            <?php
                if (!empty($item['Feed']['cache_timestamp'])):
                    $units = array('m', 'h', 'd');
                    $intervals = array(60, 60, 24);
                    $unit = 's';
                    $last = time() - $item['Feed']['cache_timestamp'];
                    foreach ($units as $k => $v) {
                        if ($last > $intervals[$k]) {
                            $unit = $v;
                            $last = floor($last / $intervals[$k]);
                        } else {
                            break;
                        }
                    }
                    echo __('Age: ') . $last . $unit;
                else:
                    echo __('Not cached');
                endif;
                if ($item['Feed']['caching_enabled'] && $isSiteAdmin):
            ?>
                    <a href="<?php echo $baseurl;?>/feeds/cacheFeeds/<?php echo h($item['Feed']['id']); ?>" title="<?php echo __('Cache feed');?>" aria-label="<?php echo __('Cache feed');?>"><span class="black fa fa-memory"></span></a>
            <?php
                endif;
            ?>
        </td>
        <td class="short action-links">
            <?php
                echo $this->Html->link('', array('action' => 'previewIndex', $item['Feed']['id']), array('class' => 'fa fa-search', 'title' => __('Explore the events remotely'), 'aria-label' => __('Explore the events remotely')));
                if (!isset($item['Feed']['event_error']) && $isSiteAdmin) {
                    if ($item['Feed']['enabled']) echo $this->Html->link('', array('action' => 'fetchFromFeed', $item['Feed']['id']), array('class' => 'fa fa-arrow-circle-down', 'title' => __('Fetch all events'), 'aria-label' => __('Fetch all events')));
                }
                if ($isSiteAdmin):
            ?>
                <a href="<?php echo $baseurl;?>/feeds/edit/<?php echo h($item['Feed']['id']); ?>" aria-label="<?php echo __('Edit');?>"><span class="black fa fa-edit" title="<?php echo __('Edit');?>">&nbsp;</span></a>
                <?php echo $this->Form->postLink('', array('action' => 'delete', h($item['Feed']['id'])), array('class' => 'fa fa-trash', 'title' => __('Delete'), 'aria-label' => __('Delete')), __('Are you sure you want to permanently remove the feed (%s)?', h($item['Feed']['name']))); ?>
            <?php endif; ?>
            <a href="<?php echo $baseurl;?>/feeds/view/<?php echo h($item['Feed']['id']); ?>.json" title="<?php echo __('Download feed metadata as JSON');?>" download><span class="fa fa-cloud-download-alt black"></span></a>
        </td>
    </tr><?php
endforeach; ?>
    </table>
    <p>
    <?php
    echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
    ));
    ?>
    </p>
    <div class="pagination">
        <ul>
        <?php
            echo $this->Paginator->prev('&laquo; ' . __('previous'), array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'prev disabled', 'escape' => false, 'disabledTag' => 'span'));
            echo $this->Paginator->numbers(array('modulus' => 20, 'separator' => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'span'));
            echo $this->Paginator->next(__('next') . ' &raquo;', array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'next disabled', 'escape' => false, 'disabledTag' => 'span'));
        ?>
        </ul>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        popoverStartup();
        $('.select').on('change', function() {
            listCheckboxesChecked();
        });
    });
</script>
<?php
    echo $this->element('/genericElements/SideMenu/side_menu', array('menuList' => 'feeds', 'menuItem' => 'index'));
