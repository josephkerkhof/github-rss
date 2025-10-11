import RepositoryController from './RepositoryController'
import BranchController from './BranchController'
import PullRequestController from './PullRequestController'
import SearchPullRequestController from './SearchPullRequestController'

const Controllers = {
    RepositoryController: Object.assign(RepositoryController, RepositoryController),
    BranchController: Object.assign(BranchController, BranchController),
    PullRequestController: Object.assign(PullRequestController, PullRequestController),
    SearchPullRequestController: Object.assign(SearchPullRequestController, SearchPullRequestController),
}

export default Controllers