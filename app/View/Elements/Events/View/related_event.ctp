<?php
    $href_url = isset($href_url) ? $href_url : $baseurl . '/events';
    $hide = isset($hide) ? $hide : false;
?>
<span class="<?php echo $hide ? 'hidden correlation-expanded-area' : '' ?>">
    <span style="display: inline-block; border: 1px solid #ddd; border-radius: 5px; padding: 3px; background-color: white;">
        <table>
            <tbody>
                <tr>
                    <td rowspan="2" style="border-right: 1px solid #ddd; padding-right: 2px; max-width: 24px; overflow: hidden; font-size: xx-small; text-overflow: ellipsis;" title="<?php echo h($related['Orgc']['name']); ?>">
                        <?php echo $this->OrgImg->getOrgImg(array('name' => $related['Orgc']['name'], 'id' => $related['Orgc']['id'], 'size' => 24)); ?>
                    </td>
                    <td style="line-height: 14px; padding-left: 2px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; max-width: 430px;">
                        <a title="<?php echo h($related['info']); ?>" href="<?php echo h($href_url) . '/' . $related['id']?>">
                            <span><?php echo h($related['info']) ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="line-height: 14px; padding-left: 2px;">
                        <i><?php echo h($related['date']); ?></i>
                        <?php if (isset($relatedEventCorrelationCount[$related['id']])): ?>
                            <b style="margin-left: 5px; float: right; cursor: help;" title="<?php echo sprintf(__('This related event contains %s unique correlation(s)'), h($relatedEventCorrelationCount[$related['id']])); ?>"> <?php echo h($relatedEventCorrelationCount[$related['id']]) ?></b>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </span>
</span>
